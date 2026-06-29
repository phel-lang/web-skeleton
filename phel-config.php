<?php

declare(strict_types=1);

use Phel\Config\PhelConfig;
use Phel\Config\ProjectLayout;

// `forProject(Flat)` sets the src/tests/format dirs for the standard layout;
// see https://phel-lang.org/documentation/configuration/. Run `phel config`
// to print the effective configuration.
return PhelConfig::forProject(ProjectLayout::Flat)
    ->withMainPhelNamespace('web-skeleton.app')
    ->withIgnoreWhenBuilding(['local.phel'])
    ->withNoCacheWhenBuilding(['web-skeleton.app'])
    // Level 2 inlines core arithmetic/bit fns and rewrites self-recursive tail
    // calls; drop to 0 to keep those nil-guard runtime checks during dev.
    ->withOptimizationLevel(2);
