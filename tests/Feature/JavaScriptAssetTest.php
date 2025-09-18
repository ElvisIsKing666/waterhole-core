<?php

declare(strict_types=1);

use Waterhole\Extend\Script;

it('should return JavaScript URLs for default bundle', function () {
    $urls = Script::urls(['default']);
    
    // Should include the main JavaScript files
    expect($urls)->toHaveCount(3);
    expect($urls[0])->toContain('index.js');
    expect($urls[1])->toContain('highlight.js');
    expect($urls[2])->toContain('emoji.js');
});

it('should return JavaScript URLs for cp bundle', function () {
    $urls = Script::urls(['cp']);
    
    // Should include the control panel JavaScript file
    expect($urls)->toHaveCount(1);
    expect($urls[0])->toContain('index2.js'); // The actual built file name
});

it('should return empty array for unknown bundle', function () {
    $urls = Script::urls(['unknown']);
    
    expect($urls)->toBeEmpty();
});

it('should return multiple bundles when requested', function () {
    $urls = Script::urls(['default', 'cp']);
    
    // Should include all JavaScript files
    expect($urls)->toHaveCount(4);
    expect($urls)->toContain('http://localhost/waterhole/index.js');
    expect($urls)->toContain('http://localhost/waterhole/highlight.js');
    expect($urls)->toContain('http://localhost/waterhole/emoji.js');
    expect($urls)->toContain('http://localhost/waterhole/index2.js'); // CP bundle
});
