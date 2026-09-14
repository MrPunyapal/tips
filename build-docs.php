<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';

use Docsmith\Docsmith;

Docsmith::make()
    ->source(__DIR__ . '/content')
    ->output(__DIR__ . '/docs')
    ->title('Laravel Tips')
    ->description('Curated Laravel, PHP, and Pest tips by Punyapal Shah.')
    ->accentColor('#ff2d20')
    ->siteUrl('https://mrpunyapal.github.io/tips')
    ->repositoryUrl('https://github.com/MrPunyapal/tips')
    ->baseUrl('/tips/')
    ->editPrefix('content/')
    ->rightSidebar()
    ->build();
