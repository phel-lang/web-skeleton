<?php

declare(strict_types=1);

use Phel\Config\PhelConfig;
use Phel\Config\PhelExportConfig;
use Phel\Config\PhelOutConfig;

return (new PhelConfig())
    ->setSrcDirs(['src'])
    ->setTestDirs(['tests'])
    ->setVendorDir('vendor')
    ->setOut((new PhelOutConfig())
        ->setDestDir('out')
        ->setMainPhelNamespace('web-skeleton\app')
        ->setMainPhpFilename('index'))
    ->setExport((new PhelExportConfig())
        ->setDirectories(['src/phel'])
        ->setNamespacePrefix('PhelGenerated')
        ->setTargetDirectory('src/PhelGenerated'))
    ->setIgnoreWhenBuilding(['src/local.phel'])
    ->setKeepGeneratedTempFiles(false)
    ->setFormatDirs(['src', 'tests']);
