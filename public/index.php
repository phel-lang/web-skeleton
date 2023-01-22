<?php

declare(strict_types=1);

use Phel\Phel;

$projectRootDir = dirname(__DIR__) . '/';

require $projectRootDir . 'vendor/autoload.php';

# php -S localhost:8080 -t public
Phel::run($projectRootDir, 'phel-web-skeleton\app');
