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
    ->withNoCacheWhenBuilding(['web-skeleton.app'])
    // Level 2 inlines core arithmetic/bit fns and rewrites self-recursive tail
    // calls; drop to 0 to keep those nil-guard runtime checks during dev.
    ->withOptimizationLevel(2);
