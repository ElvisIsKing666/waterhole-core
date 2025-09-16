<?php

namespace Waterhole\Providers;

use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class ViteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Register Vite assets for waterhole-core
        Vite::useBuildDirectory('waterhole');
    }

    public function register()
    {
        // Register Vite asset helpers
        $this->app->singleton('waterhole.vite', function () {
            return new class {
                public function getAssetUrl(string $asset): string
                {
                    return Vite::asset("resources/{$asset}");
                }

                public function getCssAssets(): array
                {
                    return [
                        Vite::asset('resources/css/global/app.css'),
                        Vite::asset('resources/css/cp/app.css'),
                    ];
                }

                public function getJsAssets(): array
                {
                    return [
                        Vite::asset('resources/js/index.ts'),
                        Vite::asset('resources/js/highlight.ts'),
                        Vite::asset('resources/js/emoji.ts'),
                        Vite::asset('resources/js/cp/index.ts'),
                    ];
                }
            };
        });
    }
}
