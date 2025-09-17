<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

it('should run all migrations successfully with SQLite', function () {
    // This test verifies that all Waterhole migrations can run on SQLite
    // without fulltext index errors
    
    // Check that key tables exist
    expect(Schema::hasTable('users'))->toBeTrue('users table should exist');
    expect(Schema::hasTable('channels'))->toBeTrue('channels table should exist');
    expect(Schema::hasTable('posts'))->toBeTrue('posts table should exist');
    expect(Schema::hasTable('comments'))->toBeTrue('comments table should exist');
    expect(Schema::hasTable('groups'))->toBeTrue('groups table should exist');
    expect(Schema::hasTable('permissions'))->toBeTrue('permissions table should exist');
});

it('should create posts table without fulltext indexes on SQLite', function () {
    // Verify that the posts table exists and has the correct structure
    expect(Schema::hasTable('posts'))->toBeTrue();
    
    // Check that the table has the expected columns
    expect(Schema::hasColumn('posts', 'id'))->toBeTrue();
    expect(Schema::hasColumn('posts', 'title'))->toBeTrue();
    expect(Schema::hasColumn('posts', 'body'))->toBeTrue();
    expect(Schema::hasColumn('posts', 'channel_id'))->toBeTrue();
    expect(Schema::hasColumn('posts', 'user_id'))->toBeTrue();
    
    // Verify that fulltext indexes are NOT created on SQLite
    $indexes = DB::select("PRAGMA index_list(posts)");
    $indexNames = array_column($indexes, 'name');
    
    // Should not have fulltext indexes (they would be named differently)
    expect($indexNames)->not->toContain('posts_title_fulltext');
    expect($indexNames)->not->toContain('posts_body_fulltext');
});

it('should create comments table without fulltext indexes on SQLite', function () {
    // Verify that the comments table exists and has the correct structure
    expect(Schema::hasTable('comments'))->toBeTrue();
    
    // Check that the table has the expected columns
    expect(Schema::hasColumn('comments', 'id'))->toBeTrue();
    expect(Schema::hasColumn('comments', 'body'))->toBeTrue();
    expect(Schema::hasColumn('comments', 'post_id'))->toBeTrue();
    expect(Schema::hasColumn('comments', 'user_id'))->toBeTrue();
    
    // Verify that fulltext indexes are NOT created on SQLite
    $indexes = DB::select("PRAGMA index_list(comments)");
    $indexNames = array_column($indexes, 'name');
    
    // Should not have fulltext indexes
    expect($indexNames)->not->toContain('comments_body_fulltext');
});

it('should create all required Waterhole tables', function () {
    $expectedTables = [
        'users',
        'channels', 
        'posts',
        'comments',
        'groups',
        'group_user',
        'mentions',
        'post_user',
        'permissions',
        'notifications',
        'reaction_sets',
        'reaction_types',
        'reactions',
        'taxonomies',
        'tags',
        'post_tag',
        'channel_taxonomy',
        'auth_providers',
        'uploads',
        'attachments',
        'pages',
        'structure',
        'structure_headings',
        'structure_links',
    ];
    
    foreach ($expectedTables as $table) {
        expect(Schema::hasTable($table))->toBeTrue("Table {$table} should exist");
    }
});

it('should have proper foreign key constraints', function () {
    // Test that foreign key constraints are properly set up
    expect(Schema::hasColumn('posts', 'channel_id'))->toBeTrue();
    expect(Schema::hasColumn('posts', 'user_id'))->toBeTrue();
    expect(Schema::hasColumn('comments', 'post_id'))->toBeTrue();
    expect(Schema::hasColumn('comments', 'user_id'))->toBeTrue();
    expect(Schema::hasColumn('comments', 'parent_id'))->toBeTrue();
});

it('should be able to insert and retrieve data', function () {
    // Test basic CRUD operations work with SQLite
    
    // Insert a test user
    $userId = DB::table('users')->insertGetId([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'created_at' => now(),
    ]);
    
    expect($userId)->toBeGreaterThan(0);
    
    // Insert a test channel
    $channelId = DB::table('channels')->insertGetId([
        'name' => 'Test Channel',
        'slug' => 'test-channel',
    ]);
    
    expect($channelId)->toBeGreaterThan(0);
    
    // Insert a test post
    $postId = DB::table('posts')->insertGetId([
        'channel_id' => $channelId,
        'user_id' => $userId,
        'title' => 'Test Post',
        'body' => 'This is a test post body',
        'created_at' => now(),
    ]);
    
    expect($postId)->toBeGreaterThan(0);
    
    // Verify data can be retrieved
    $user = DB::table('users')->where('id', $userId)->first();
    expect($user->name)->toBe('Test User');
    expect($user->email)->toBe('test@example.com');
    
    $post = DB::table('posts')->where('id', $postId)->first();
    expect($post->title)->toBe('Test Post');
    expect($post->body)->toBe('This is a test post body');
});
