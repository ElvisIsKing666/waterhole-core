<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\AssetList;

/**
 * Manage JavaScript asset bundles.
 *
 * In addition to files, you can also add callbacks which return JS code.
 *
 * Waterhole will simply concatenate the scripts together into bundles. You are
 * responsible for doing any transpiling prior.
 */
class Script
{
    use AssetList;

    const CACHE_KEY = 'waterhole.script';
    const FILE_EXTENSION = 'js';
}

// Vite will handle asset compilation and manifest generation
// Assets are now registered via vite.config.js and accessed through Vite's manifest
