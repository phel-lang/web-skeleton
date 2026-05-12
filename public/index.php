<?php

declare(strict_types=1);

use Phel\Phel;

$projectRoot = dirname(__DIR__);

require $projectRoot . '/vendor/autoload.php';

$compiled = $projectRoot . '/out/index.php';

if (is_file($compiled)) {
    require $compiled;
    return;
}

Phel::run($projectRoot, 'web-skeleton.app');
