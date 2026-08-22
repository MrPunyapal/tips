---
category: "PHP"
tags: ["PHP", "Rector", "Enums", "Refactoring", "Blade", "Tooling"]
date: "2024-05-20"
author: "Punyapal Shah"
author_url: "https://x.com/MrPunyapal"
subcategory: "Tooling"
---

# Refactor PHP Enum Cases from snake_case to PascalCase with Rector

> Automate renaming PHP 8.1+ enum cases from SNAKE_CASE to PascalCase across PHP classes and Blade templates using Rector and an Artisan refactoring script.

When PHP 8.1 backed enums were introduced, many projects originally named enum cases in uppercase snake case (`case PENDING_REVIEW;`), matching older class constant conventions. Modern PHP and Laravel coding style standards (including PER-CS and Laravel Pint) recommend PascalCase for enum cases (`case PendingReview;`).

Renaming enum cases across a large production application manually is error-prone. By combining a custom Rector rule with an Artisan Blade updater, you can refactor all enum declarations, references, and Blade view usages automatically.

## 1. Custom Rector Rule for Enum Cases

```php
namespace AppRector;

use PhpParserNode;
use PhpParserNodeExprClassConstFetch;
use PhpParserNodeIdentifier;
use PhpParserNodeStmtEnumCase;
use PHPStanReflectionReflectionProvider;
use RectorRectorAbstractRector;
use SymplifyRuleDocGeneratorValueObjectCodeSampleCodeSample;
use SymplifyRuleDocGeneratorValueObjectRuleDefinition;

final class RenameEnumCasesToPascalCaseRector extends AbstractRector
{
    public function __construct(
        private ReflectionProvider $reflectionProvider
    ) {}

    public function getNodeTypes(): array
    {
        return [EnumCase::class, ClassConstFetch::class];
    }

    public function refactor(Node $node): ?Node
    {
        if ($node instanceof EnumCase) {
            return $this->refactorEnumCase($node);
        }

        if ($node instanceof ClassConstFetch) {
            return $this->refactorEnumCaseFetch($node);
        }

        return null;
    }

    private function refactorEnumCase(EnumCase $node): ?Node
    {
        $oldName = $this->getName($node->name);
        $newName = $this->convertSnakeToPascalCase($oldName);

        if ($oldName !== $newName) {
            $node->name = new Identifier($newName);
            return $node;
        }

        return null;
    }

    private function refactorEnumCaseFetch(ClassConstFetch $node): ?Node
    {
        $objectType = $this->getType($node->class);

        if ($objectType->isEnum()->yes()) {
            $oldName = $this->getName($node->name);
            if ($oldName === 'class' || $oldName === null) {
                return null;
            }

            $newName = $this->convertSnakeToPascalCase($oldName);
            if ($oldName !== $newName) {
                $node->name = new Identifier($newName);
                return $node;
            }
        }

        return null;
    }

    private function convertSnakeToPascalCase(string $name): string
    {
        return str_replace(' ', '', ucwords(str_replace('_', ' ', mb_strtolower($name))));
    }

    public function getRuleDefinition(): RuleDefinition
    {
        return new RuleDefinition('Convert SNAKE_CASE enum cases to PascalCase', [
            new CodeSample(
                'enum Status { case PENDING_PAYMENT; }',
                'enum Status { case PendingPayment; }'
            ),
        ]);
    }
}
```

## 2. Refactoring Blade Templates

Because Rector parses PHP files rather than Blade templates, use an Artisan command to update enum case usages inside `resources/views/**/*.blade.php`:

```php
namespace AppConsoleCommands;

use IlluminateConsoleCommand;
use IlluminateSupportFacadesFile;

class RefactorBladeEnumsCommand extends Command
{
    protected $signature = 'refactor:blade-enums';
    protected $description = 'Refactor enum cases in Blade views from SNAKE_CASE to PascalCase';

    public function handle(): int
    {
        $views = File::allFiles(resource_path('views'));

        foreach ($views as $file) {
            if ($file->getExtension() !== 'php') {
                continue;
            }

            $content = File::get($file->getRealPath());

            // Match patterns like Status::PENDING_PAYMENT
            $updated = preg_replace_callback('/([A-Z][A-Za-z0-9]+)::([A-Z0-9_]+)/', function ($matches) {
                $class = $matches[1];
                $case = $matches[2];

                if ($case === 'class') {
                    return $matches[0];
                }

                $pascalCase = str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower($case))));
                return "{$class}::{$pascalCase}";
            }, $content);

            if ($content !== $updated) {
                File::put($file->getRealPath(), $updated);
                $this->line("Updated view: " . $file->getRelativePathname());
            }
        }

        $this->info('Blade enum refactoring completed.');
        return Command::SUCCESS;
    }
}
```

## Summary

- Use Rector to safely refactor Enum definitions and PHP references via AST inspection and PHPStan static reflection.
- Run a targeted Blade regex migration script to update template references without manual search-and-replace.
- Standardizes your enum architecture to PascalCase conventions across the entire application.
