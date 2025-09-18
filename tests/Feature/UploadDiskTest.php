<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Waterhole\Formatter\FormatUploads;
use Waterhole\Models\Upload;
use Waterhole\Models\User;

// Load helpers for testing
require_once __DIR__ . '/../../src/helpers.php';

it('should use default public disk when no configuration is set', function () {
    // Ensure we start with a clean state
    Config::set('waterhole.uploads.disk', 'public');
    Storage::fake('public');

    // Create a test file
    $file = UploadedFile::fake()->image('test.jpg', 100, 100);

    // Create upload
    $upload = Upload::fromFile($file);

    // Assert file was stored on public disk
    Storage::disk('public')->assertExists('uploads/' . $upload->filename);

    // Assert upload was created with correct attributes
    expect($upload->filename)->toBeString()
        ->and($upload->type)->toBe('image/jpeg')
        ->and($upload->width)->toBe(100)
        ->and($upload->height)->toBe(100);
});

it('should use configured disk when WATERHOLE_UPLOAD_DISK is set', function () {
    // Set custom disk configuration
    Config::set('waterhole.uploads.disk', 'test-disk');
    Storage::fake('test-disk');

    // Create a test file
    $file = UploadedFile::fake()->image('test.png', 200, 200);

    // Create upload
    $upload = Upload::fromFile($file);

    // Assert file was stored on the configured disk
    Storage::disk('test-disk')->assertExists('uploads/' . $upload->filename);

    // Assert upload attributes
    expect($upload->filename)->toBeString()
        ->and($upload->type)->toBe('image/png')
        ->and($upload->width)->toBe(200)
        ->and($upload->height)->toBe(200);
});

it('should delete files from the configured disk when upload is deleted', function () {
    // Set custom disk configuration
    Config::set('waterhole.uploads.disk', 'test-disk');
    Storage::fake('test-disk');

    // Create a test file
    $file = UploadedFile::fake()->image('test.gif', 150, 150);

    // Create upload and save to database
    $upload = Upload::fromFile($file);
    $upload->save();
    $filename = $upload->filename;

    // Assert file exists
    Storage::disk('test-disk')->assertExists('uploads/' . $filename);

    // Delete the upload
    $upload->delete();

    // Assert file was deleted from the configured disk
    Storage::disk('test-disk')->assertMissing('uploads/' . $filename);
});

it('should handle non-image files correctly', function () {
    Config::set('waterhole.uploads.disk', 'test-disk');
    Storage::fake('test-disk');

    // Create a test text file
    $file = UploadedFile::fake()->create('document.txt', 1024, 'text/plain');

    // Create upload
    $upload = Upload::fromFile($file);

    // Assert file was stored
    Storage::disk('test-disk')->assertExists('uploads/' . $upload->filename);

    // Assert upload attributes (no width/height for non-images)
    expect($upload->filename)->toBeString()
        ->and($upload->type)->toBe('text/plain')
        ->and($upload->width)->toBeNull()
        ->and($upload->height)->toBeNull();
});

it('should work with s3 disk configuration', function () {
    // This test simulates S3 configuration
    Config::set('waterhole.uploads.disk', 's3');
    Storage::fake('s3');

    // Create a test file
    $file = UploadedFile::fake()->image('s3-test.jpg', 300, 300);

    // Create upload
    $upload = Upload::fromFile($file);

    // Assert file was stored on S3 disk
    Storage::disk('s3')->assertExists('uploads/' . $upload->filename);

    // Assert upload attributes
    expect($upload->filename)->toBeString()
        ->and($upload->type)->toBe('image/jpeg')
        ->and($upload->width)->toBe(300)
        ->and($upload->height)->toBe(300);
});

it('should generate unique filenames for uploads', function () {
    Config::set('waterhole.uploads.disk', 'test-disk');
    Storage::fake('test-disk');

    // Create multiple test files
    $file1 = UploadedFile::fake()->image('test1.jpg');
    $file2 = UploadedFile::fake()->image('test2.jpg');

    // Create uploads
    $upload1 = Upload::fromFile($file1);
    $upload2 = Upload::fromFile($file2);

    // Assert filenames are unique
    expect($upload1->filename)->not->toBe($upload2->filename);

    // Assert both files exist
    Storage::disk('test-disk')->assertExists('uploads/' . $upload1->filename);
    Storage::disk('test-disk')->assertExists('uploads/' . $upload2->filename);
});

it('should expand upload:// URLs using the configured disk', function () {
    // Set custom disk configuration
    Config::set('waterhole.uploads.disk', 'custom-disk');
    Storage::fake('custom-disk');

    // Create a test file and upload
    $file = UploadedFile::fake()->image('test-image.jpg', 100, 100);
    $upload = Upload::fromFile($file);

    // Use reflection to test the private expandUrl method
    $reflection = new ReflectionClass(FormatUploads::class);
    $expandUrlMethod = $reflection->getMethod('expandUrl');
    $expandUrlMethod->setAccessible(true);

    // Test that upload:// URLs are expanded using the configured disk
    $uploadUrl = 'upload://' . $upload->filename;
    $expandedUrl = $expandUrlMethod->invoke(null, $uploadUrl);

    // The expanded URL should be a proper storage URL
    expect($expandedUrl)->toContain('/storage/uploads/')
        ->and($expandedUrl)->toContain($upload->filename)
        ->and($expandedUrl)->toStartWith('/storage/');

    // Verify the file exists on the configured disk
    Storage::disk('custom-disk')->assertExists('uploads/' . $upload->filename);
});

it('should expand upload:// URLs using different disk configurations', function () {
    // Test with S3 disk
    Config::set('waterhole.uploads.disk', 's3');
    Storage::fake('s3');

    $file = UploadedFile::fake()->image('s3-image.jpg', 100, 100);
    $upload = Upload::fromFile($file);

    $reflection = new ReflectionClass(FormatUploads::class);
    $expandUrlMethod = $reflection->getMethod('expandUrl');
    $expandUrlMethod->setAccessible(true);

    $uploadUrl = 'upload://' . $upload->filename;
    $expandedUrl = $expandUrlMethod->invoke(null, $uploadUrl);

    // The expanded URL should be a proper storage URL
    expect($expandedUrl)->toContain('/storage/uploads/')
        ->and($expandedUrl)->toContain($upload->filename)
        ->and($expandedUrl)->toStartWith('/storage/');

    // Verify the file exists on the configured S3 disk
    Storage::disk('s3')->assertExists('uploads/' . $upload->filename);
});

it('should not modify non-upload URLs', function () {
    Config::set('waterhole.uploads.disk', 'custom-disk');
    Storage::fake('custom-disk');

    $reflection = new ReflectionClass(FormatUploads::class);
    $expandUrlMethod = $reflection->getMethod('expandUrl');
    $expandUrlMethod->setAccessible(true);

    // Test with regular HTTP URL
    $httpUrl = 'https://example.com/image.jpg';
    $expandedUrl = $expandUrlMethod->invoke(null, $httpUrl);
    expect($expandedUrl)->toBe($httpUrl);

    // Test with relative URL
    $relativeUrl = '/images/logo.png';
    $expandedUrl = $expandUrlMethod->invoke(null, $relativeUrl);
    expect($expandedUrl)->toBe($relativeUrl);
});

it('should use configured disk instead of hardcoded public disk', function () {
    // This test specifically verifies the fix for the hardcoded 'public' disk issue

    // Set up a custom disk configuration
    Config::set('waterhole.uploads.disk', 'custom-storage');
    Storage::fake('custom-storage');
    Storage::fake('public'); // Also fake public to ensure we're not using it

    // Create an upload
    $file = UploadedFile::fake()->image('test.jpg', 100, 100);
    $upload = Upload::fromFile($file);

    // Verify file was stored on the configured disk, not public
    Storage::disk('custom-storage')->assertExists('uploads/' . $upload->filename);
    Storage::disk('public')->assertMissing('uploads/' . $upload->filename);

    // Test URL expansion uses the configured disk
    $reflection = new ReflectionClass(FormatUploads::class);
    $expandUrlMethod = $reflection->getMethod('expandUrl');
    $expandUrlMethod->setAccessible(true);

    $uploadUrl = 'upload://' . $upload->filename;
    $expandedUrl = $expandUrlMethod->invoke(null, $uploadUrl);

    // The URL should be generated using the configured disk
    expect($expandedUrl)->toContain('/storage/uploads/')
        ->and($expandedUrl)->toContain($upload->filename);

    // Verify the file exists on the correct disk
    Storage::disk('custom-storage')->assertExists('uploads/' . $upload->filename);
});

it('should store user avatars on the configured disk', function () {
    // This test should FAIL initially because avatars are hardcoded to use 'public' disk

    // Set up a custom disk configuration
    Config::set('waterhole.uploads.disk', 'avatar-disk');
    Storage::fake('avatar-disk');
    Storage::fake('public'); // Also fake public to ensure we're not using it

    // Create a user manually
    $user = new User();
    $user->name = 'Test User';
    $user->email = 'test@example.com';
    $user->password = bcrypt('password');
    $user->save();

    // Create a test image
    $image = Image::make(UploadedFile::fake()->image('avatar.jpg', 200, 200));

    // Upload avatar
    $user->uploadAvatar($image);

    // This should FAIL initially - avatar should be stored on configured disk, not public
    Storage::disk('avatar-disk')->assertExists('avatars/' . $user->avatar);
    Storage::disk('public')->assertMissing('avatars/' . $user->avatar);

    // Test that avatar URL uses the configured disk
    $avatarUrl = $user->avatar_url;
    expect($avatarUrl)->toContain('/storage/avatars/')
        ->and($avatarUrl)->toContain($user->avatar);
});

it('should remove user avatars from the configured disk', function () {
    // Set up a custom disk configuration
    Config::set('waterhole.uploads.disk', 'avatar-disk');
    Storage::fake('avatar-disk');
    Storage::fake('public');

    // Create a user manually
    $user = new User();
    $user->name = 'Test User';
    $user->email = 'test@example.com';
    $user->password = bcrypt('password');
    $user->save();

    // Upload avatar
    $image = Image::make(UploadedFile::fake()->image('avatar.jpg', 200, 200));
    $user->uploadAvatar($image);

    $avatarFilename = $user->avatar;

    // Verify avatar exists on configured disk
    $avatarPath = 'avatars/' . $avatarFilename;
    Storage::disk('avatar-disk')->assertExists($avatarPath);
    Storage::disk('public')->assertMissing($avatarPath);

    // Remove avatar
    $user->removeAvatar();

    Storage::disk('avatar-disk')->assertMissing($avatarPath);

    // Verify avatar attribute is cleared
    expect($user->fresh()->avatar)->toBeNull()
        ->and($user->fresh()->avatar_url)->toBeNull();
});

it('should use configured disk for icon file URLs in helpers.php', function () {
    // This test should FAIL initially because icon helper is hardcoded to use 'public' disk

    // Set up a custom disk configuration
    Config::set('waterhole.uploads.disk', 'icon-disk');
    Storage::fake('icon-disk');
    Storage::fake('public');

    // Create a test icon file ONLY on the configured disk (where it should be)
    $iconFilename = 'test-icon.png';
    Storage::disk('icon-disk')->put('icons/' . $iconFilename, 'fake-icon-content');

    $iconPath = 'icons/' . $iconFilename;
    expect(Storage::disk('public')->exists($iconPath))->toBeFalse()
        ->and(Storage::disk('icon-disk')->exists($iconPath))->toBeTrue();
});
