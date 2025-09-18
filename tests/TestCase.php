<?php

declare(strict_types=1);

namespace Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Waterhole\Providers\WaterholeServiceProvider;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            WaterholeServiceProvider::class,
            \Laravel\Socialite\SocialiteServiceProvider::class,
            \Intervention\Image\ImageServiceProvider::class,
            \BladeUI\Icons\BladeIconsServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Set up SQLite database for testing
        $app['config']->set('database.default', 'testing');
        $app['config']->set('database.connections.testing', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => 'waterhole_',
            'foreign_key_constraints' => true,
        ]);

        // Configure Waterhole to use the testing database
        $app['config']->set('waterhole.system.database', 'testing');
        
        // Set up telescope to use testing database
        $app['config']->set('telescope.storage.database.connection', 'testing');
        
        // Set up filesystem for testing
        $app['config']->set('filesystems.disks.public', [
            'driver' => 'local',
            'root' => storage_path('app/public'),
        ]);
        
        $app['config']->set('filesystems.disks.test-disk', [
            'driver' => 'local',
            'root' => storage_path('app/test-disk'),
        ]);
        
        $app['config']->set('filesystems.disks.s3', [
            'driver' => 's3',
            'key' => 'test',
            'secret' => 'test',
            'region' => 'us-east-1',
            'bucket' => 'test-bucket',
        ]);
        
        // Set up icons configuration
        $app['config']->set('blade-icons', [
            'class' => 'icon',
            'attributes' => [],
            'fallback' => '',
            'components' => [
                'disabled' => false,
                'default' => 'icon',
                'class' => 'icon',
                'attributes' => [],
            ],
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        
        // Run migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
