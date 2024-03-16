<?php

declare(strict_types=1);

use Phel\Phel;

$projectRootDir = dirname(__DIR__);
require $projectRootDir . '/vendor/autoload.php';

$appFile = $projectRootDir . "/out/index.php";

if (file_exists($appFile)) {
    require_once $appFile;
} else {
    Phel::run($projectRootDir, 'web-skeleton\app');
}

