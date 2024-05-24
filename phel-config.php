<?php

declare(strict_types=1);

use Phel\Config\PhelConfig;
use Phel\Config\PhelExportConfig;
use Phel\Config\PhelBuildConfig;

return (new PhelConfig())
    ->setSrcDirs(['src'])
    ->setTestDirs(['tests'])
    ->setVendorDir('vendor')
    ->setKeepGeneratedTempFiles(false)
    ->setFormatDirs(['src', 'tests'])
    ->setBuildConfig((new PhelBuildConfig())
        ->setMainPhelNamespace('web-skeleton\app')
        ->setMainPhpPath('out/index.php'))
    ->setExportConfig((new PhelExportConfig())
        ->setFromDirectories(['src/phel'])
        ->setNamespacePrefix('PhelGenerated')
        ->setTargetDirectory('src/PhelGenerated'))
    ->setIgnoreWhenBuilding(['local.phel'])
    ->setNoCacheWhenBuilding(['web_skeleton/app'])
;
