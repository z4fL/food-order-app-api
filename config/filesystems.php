<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application for file storage.
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Below you may configure as many filesystem disks as necessary, and you
    | may even configure multiple disks for the same driver. Examples for
    | most supported storage drivers are configured here for reference.
    |
    | Supported drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'serve' => true,
            'throw' => false,
            'report' => false,
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
            'throw' => false,
            'report' => false,
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
            'report' => false,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Symbolic Links
    |--------------------------------------------------------------------------
    |
    | Here you may configure the symbolic links that will be created when the
    | `storage:link` Artisan command is executed. The array keys should be
    | the locations of the links and the values should be their targets.
    |
    */

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],


    /**
     * Cloudinary filesystem disk configuration.
     *
     * This configuration array defines the settings required to connect and interact with the Cloudinary storage service.
     *
     * - driver: The filesystem driver to use ('cloudinary').
     * - key: Your Cloudinary API key, loaded from the CLOUDINARY_KEY environment variable.
     * - secret: Your Cloudinary API secret, loaded from the CLOUDINARY_SECRET environment variable.
     * - cloud: Your Cloudinary cloud name, loaded from the CLOUDINARY_CLOUD_NAME environment variable.
     * - url: The Cloudinary base URL, loaded from the CLOUDINARY_URL environment variable.
     * - secure: Boolean flag to determine if secure URLs should be used, defaults to true. Loaded from CLOUDINARY_SECURE.
     * - prefix: Optional path prefix for all Cloudinary assets, loaded from CLOUDINARY_PREFIX.
     */
    'cloudinary' => [
        'driver' => 'cloudinary',
        'key' => env('CLOUDINARY_KEY'),
        'secret' => env('CLOUDINARY_SECRET'),
        'cloud' => env('CLOUDINARY_CLOUD_NAME'),
        'url' => env('CLOUDINARY_URL'),
        'secure' => (bool) env('CLOUDINARY_SECURE', true),
        'prefix' => env('CLOUDINARY_PREFIX'),
    ],
];
