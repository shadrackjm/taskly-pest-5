<?php

declare(strict_types=1);

use Pest\Rector\Rules\UseToContainOnlyInstancesOfRector;
use Pest\Rector\Set\PestSetList;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/tests',
    ])
    ->withSets([
        PestSetList::CODING_STYLE,
    ])
    ->withSkip([
        // ExpectationsTest exists to DEMONSTRATE the `each` modifier for episode 2.
        // Rector is mechanically right that toContainOnlyInstancesOf() is tighter here,
        // and wrong about the intent. This is the one place we keep the longer form.
        UseToContainOnlyInstancesOfRector::class => [
            __DIR__.'/tests/Unit/ExpectationsTest.php',
        ],
    ]);
