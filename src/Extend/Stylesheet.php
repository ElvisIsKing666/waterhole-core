<?php

namespace Waterhole\Extend;

use Waterhole\Extend\Concerns\AssetList;

/**
 * Manage stylesheet asset bundles.
 *
 * In addition to files, you can also add callbacks which return CSS code.
 *
 * Waterhole will simply concatenate the stylesheets together into bundles. You
 * are responsible for doing any transpiling prior.
 */
class Stylesheet
{
    use AssetList;

    const CACHE_KEY = 'waterhole.stylesheet';
    const FILE_EXTENSION = 'css';
}

// Vite will handle asset compilation and manifest generation
// Assets are now registered via vite.config.js and accessed through Vite's manifest
