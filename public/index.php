<?php

declare(strict_types=1);

use Phel\Phel;

$phelMode = getenv('PHEL_MODE') ?: 'slow';

$projectRootDir = dirname(__DIR__);

require $projectRootDir . '/vendor/autoload.php';

if ($phelMode === 'slow') {
    Phel::run($projectRootDir, 'phel-web-skeleton\app');
} else {
    require_once $projectRootDir . "/out/phel_web_skeleton/app.php";
}

