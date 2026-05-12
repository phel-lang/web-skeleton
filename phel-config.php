<?php

declare(strict_types=1);

use Phel\Config\PhelBuildConfig;
use Phel\Config\PhelConfig;
use Phel\Config\PhelExportConfig;

return (new PhelConfig())
    ->withSrcDirs(['src'])
    ->withTestDirs(['tests'])
    ->withVendorDir('vendor')
    ->withKeepGeneratedTempFiles(false)
    ->withFormatDirs(['src', 'tests'])
    ->withBuildConfig((new PhelBuildConfig())
        ->withMainPhelNamespace('web-skeleton.app')
        ->withMainPhpPath('out/index.php'))
    ->withExportConfig((new PhelExportConfig())
        ->withFromDirectories(['src/phel'])
        ->withNamespacePrefix('PhelGenerated')
        ->withTargetDirectory('src/PhelGenerated'))
    ->withIgnoreWhenBuilding(['local.phel'])
    ->withNoCacheWhenBuilding(['web-skeleton.app']);
