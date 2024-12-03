<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default filesystem disk that should be used
    | by the framework. The "local" disk, as well as a variety of cloud
    | based disks are available to your application. Just store away!
    |
    */

    'default' => env('FILESYSTEM_DISK', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been set up for each driver as an example of the required values.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        'public_uploads' => [
            'driver' => 'local',
            'root'   => public_path() . '/uploads',
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
            'throw' => false,
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
        ],

        'avatar_disk' => [
            'driver' => 'local',
            'root' => public_path('avatar'),
            'url' => env('APP_URL') . '/avatar',
            'visibility' => 'public',
            'throw' => false,
        ],

        'picture_disk' => [
            'driver' => 'local',
            'root' => public_path('picture'),
            'url' => env('APP_URL') . '/picture',
            'visibility' => 'public',
            'throw' => false,
        ],

        'purchase_disk' => [
            'driver' => 'local',
            'root' => public_path('purchase_files'),
            'url' => env('APP_URL') . '/purchase_files',
            'visibility' => 'public',
            'throw' => false,
        ],

        'purchase_non_disk' => [
            'driver' => 'local',
            'root' => public_path('purchase_non_files'),
            'url' => env('APP_URL') . '/purchase_non_files',
            'visibility' => 'public',
            'throw' => false,
        ],

        'sales_disk' => [
            'driver' => 'local',
            'root' => public_path('sales_files'),
            'url' => env('APP_URL') . '/sales_files',
            'visibility' => 'public',
            'throw' => false,
        ],

        'sales_non_disk' => [
            'driver' => 'local',
            'root' => public_path('sales_non_files'),
            'url' => env('APP_URL') . '/sales_non_files',
            'visibility' => 'public',
            'throw' => false,
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

];
