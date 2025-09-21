<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Upload Disk
    |--------------------------------------------------------------------------
    |
    | The disk to use for storing uploaded files. This can be configured via
    | the WATERHOLE_UPLOAD_DISK environment variable. Defaults to 'public'.
    |
    */

    'disk' => env('WATERHOLE_UPLOAD_DISK', 'public'),

    /*
    |--------------------------------------------------------------------------
    | Upload Path
    |--------------------------------------------------------------------------
    |
    | The path on disk to use for storing uploaded files.
    |
    */

    'path' => env('WATERHOLE_UPLOAD_PATH', 'uploads/'),

    /*
    |--------------------------------------------------------------------------
    | Allowed MIME Types
    |--------------------------------------------------------------------------
    |
    | Uploaded files must match one of the MIME types listed here. Values can
    | be either a MIME type (`text/plain`) or a file extension (`jpg`). If
    | empty, no validation will take place and all MIME types will be allowed.
    |
    */

    'allowed_mimetypes' => [],

    /*
    |--------------------------------------------------------------------------
    | Maximum Upload Size (Kilobytes)
    |--------------------------------------------------------------------------
    |
    | Any uploads larger than this will be rejected. Make sure this value is
    | below the size limit of POST requests enforced by your webserver, as
    | well as PHP's `upload_max_filesize` and `post_max_size` settings.
    |
    */

    'max_upload_size' => 5120,
];
