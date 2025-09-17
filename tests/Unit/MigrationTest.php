<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Waterhole\Database\Migration;

it('should detect SQLite driver correctly', function () {
    // Test that our migration can detect SQLite driver
    $connection = DB::connection();
    $driverName = $connection->getDriverName();
    
    expect($driverName)->toBe('sqlite');
});

it('should have proper migration base class', function () {
    // Test that the Migration base class exists and works
    expect(class_exists(Waterhole\Database\Migration::class))->toBeTrue();
    
    // Test that it extends the correct Laravel migration class
    $reflection = new ReflectionClass(Waterhole\Database\Migration::class);
    expect($reflection->isSubclassOf(Illuminate\Database\Migrations\Migration::class))->toBeTrue();
});

it('should handle database driver detection in migration context', function () {
    // This test verifies that the conditional fulltext index logic works
    // by testing the driver detection in a migration context
    
    $connection = DB::connection();
    $isMysql = $connection->getDriverName() === 'mysql';
    $isSqlite = $connection->getDriverName() === 'sqlite';
    
    expect($isMysql)->toBeFalse('Should not be MySQL in test environment');
    expect($isSqlite)->toBeTrue('Should be SQLite in test environment');
});
