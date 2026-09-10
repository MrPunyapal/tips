<?php

declare(strict_types=1);

$tipsRoot = dirname(__DIR__);

const KNOWN_WORDS = [
    'php'   => 'PHP',
    'css'   => 'CSS',
    'api'   => 'API',
    'dom'   => 'DOM',
    'mysql' => 'MySQL',
    'sql'   => 'SQL',
    'ui'    => 'UI',
    'cli'   => 'CLI',
    'rss'   => 'RSS',
    'html'  => 'HTML',
    'http'  => 'HTTP',
    'js'    => 'JS',
    'ts'    => 'TS',
    'url'   => 'URL',
];

function formatTitle(string $slug): string
{
    $words = explode('-', $slug);
    $formatted = array_map(function (string $word): string {
        $lower = strtolower($word);
        return KNOWN_WORDS[$lower] ?? ucfirst($word);
    }, $words);

    return str_replace('HTTP API', 'HTTP & API', implode(' ', $formatted));
}

function generateIndexMarkdown(string $root, string $eol = "\n"): array
{
    $contentDir = $root . DIRECTORY_SEPARATOR . 'content';
    if (!is_dir($contentDir)) {
        return ['totalTips' => 0, 'markdown' => ''];
    }

    $categories = array_values(array_filter(scandir($contentDir), function (string $item) use ($contentDir): bool {
        return !in_array($item, ['.', '..'], true) && is_dir($contentDir . DIRECTORY_SEPARATOR . $item);
    }));

    $catData = [];
    $totalTips = 0;

    foreach ($categories as $cat) {
        $catPath = $contentDir . DIRECTORY_SEPARATOR . $cat;
        $subdirs = array_values(array_filter(scandir($catPath), function (string $item) use ($catPath): bool {
            return !in_array($item, ['.', '..'], true) && is_dir($catPath . DIRECTORY_SEPARATOR . $item);
        }));

        sort($subdirs);
        $subList = [];
        $catCount = 0;

        foreach ($subdirs as $sub) {
            $subPath = $catPath . DIRECTORY_SEPARATOR . $sub;
            $files = array_values(array_filter(scandir($subPath), function (string $f): bool {
                return str_ends_with(strtolower($f), '.md') && strtolower($f) !== 'readme.md';
            }));

            $fileCount = count($files);
            if ($fileCount > 0) {
                $subList[] = [
                    'slug'    => $sub,
                    'title'   => formatTitle($sub),
                    'count'   => $fileCount,
                    'relPath' => "content/{$cat}/{$sub}",
                ];
                $catCount += $fileCount;
            }
        }

        if ($catCount > 0) {
            $catData[] = [
                'slug'          => $cat,
                'title'         => formatTitle($cat),
                'count'         => $catCount,
                'subcategories' => $subList,
            ];
            $totalTips += $catCount;
        }
    }

    // Sort categories by tip count descending, then alphabetical
    usort($catData, function (array $a, array $b): int {
        return ($b['count'] <=> $a['count']) ?: strcmp($a['title'], $b['title']);
    });

    $lines = [];
    foreach ($catData as $cat) {
        $lines[] = "- **{$cat['title']} ({$cat['count']})**";
        foreach ($cat['subcategories'] as $sub) {
            $lines[] = "  - [{$sub['title']} ({$sub['count']})]({$sub['relPath']})";
        }
    }

    return [
        'totalTips' => $totalTips,
        'markdown'  => implode($eol, $lines),
    ];
}

function updateReadme(string $tipsRoot): void
{
    $readmePath = $tipsRoot . DIRECTORY_SEPARATOR . 'README.md';
    if (!file_exists($readmePath)) {
        fwrite(STDERR, "README.md not found at {$readmePath}\n");
        exit(1);
    }

    $originalContent = file_get_contents($readmePath);
    $eol = str_contains($originalContent, "\r\n") ? "\r\n" : "\n";

    $data = generateIndexMarkdown($tipsRoot, $eol);
    $totalTips = $data['totalTips'];
    $markdown = $data['markdown'];

    $startMarker = '<!-- TIPS_INDEX:START -->';
    $endMarker = '<!-- TIPS_INDEX:END -->';
    $newBlock = "{$startMarker}{$eol}{$markdown}{$eol}{$endMarker}";

    $readmeContent = $originalContent;
    if (str_contains($readmeContent, $startMarker) && str_contains($readmeContent, $endMarker)) {
        $startIndex = strpos($readmeContent, $startMarker);
        $endIndex = strpos($readmeContent, $endMarker) + strlen($endMarker);
        $readmeContent = substr($readmeContent, 0, $startIndex) . $newBlock . substr($readmeContent, $endIndex);
    } else {
        $heading = '## Category & Subcategory Directory Index';
        if (str_contains($readmeContent, $heading)) {
            $headingIndex = strpos($readmeContent, $heading);
            $nextHeadingIndex = strpos($readmeContent, "{$eol}## ", $headingIndex + strlen($heading));
            $before = substr($readmeContent, 0, $headingIndex);
            $after = $nextHeadingIndex !== false ? substr($readmeContent, $nextHeadingIndex) : '';
            $readmeContent = "{$before}{$heading}{$eol}{$eol}Explore {$totalTips} engineering tips directly in the repository by category:{$eol}{$eol}{$newBlock}{$after}";
        }
    }

    $readmeContent = preg_replace(
        '/Explore (?:\*\*)?\d+(?:\*\*)? engineering tips/',
        "Explore **{$totalTips}** engineering tips",
        $readmeContent
    );

    if ($readmeContent === $originalContent) {
        echo "✅ README.md is already up to date ({$totalTips} tips across categories and subcategories).\n";
        return;
    }

    file_put_contents($readmePath, $readmeContent);
    echo "✅ README.md updated successfully with {$totalTips} tips across categories and subcategories.\n";
}

updateReadme($tipsRoot);
