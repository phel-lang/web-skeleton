<?php

declare(strict_types=1);

use Phel\Config\PhelBuildConfig;
use Phel\Config\PhelConfig;

return (new PhelConfig())
    ->withSrcDirs(['src'])
    ->withTestDirs(['tests'])
    ->withVendorDir('vendor')
    ->withKeepGeneratedTempFiles(false)
    ->withFormatDirs(['src', 'tests'])
    ->withBuildConfig((new PhelBuildConfig())
        ->withMainPhelNamespace('web-skeleton.app')
        ->withMainPhpPath('out/index.php'))
    ->withIgnoreWhenBuilding(['local.phel'])
    ->withNoCacheWhenBuilding(['web-skeleton.app']);
