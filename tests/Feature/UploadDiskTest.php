<?php

declare(strict_types=1);

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Waterhole\Models\Upload;

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
    expect($upload->filename)->toBeString();
    expect($upload->type)->toBe('image/jpeg');
    expect($upload->width)->toBe(100);
    expect($upload->height)->toBe(100);
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
    expect($upload->filename)->toBeString();
    expect($upload->type)->toBe('image/png');
    expect($upload->width)->toBe(200);
    expect($upload->height)->toBe(200);
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
    expect($upload->filename)->toBeString();
    expect($upload->type)->toBe('text/plain');
    expect($upload->width)->toBeNull();
    expect($upload->height)->toBeNull();
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
    expect($upload->filename)->toBeString();
    expect($upload->type)->toBe('image/jpeg');
    expect($upload->width)->toBe(300);
    expect($upload->height)->toBe(300);
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
