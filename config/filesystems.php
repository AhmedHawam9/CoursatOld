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

    'default' => env('FILESYSTEM_DRIVER', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Default Cloud Filesystem Disk
    |--------------------------------------------------------------------------
    |
    | Many applications store files both locally and in the cloud. For this
    | reason, you may specify a default "cloud" driver here. This driver
    | will be bound as the Cloud disk implementation in the container.
    |
    */

    'cloud' => env('FILESYSTEM_CLOUD', 's3'),

    /*
    |--------------------------------------------------------------------------
    | Filesystem Disks
    |--------------------------------------------------------------------------
    |
    | Here you may configure as many filesystem "disks" as you wish, and you
    | may even configure multiple disks of the same driver. Defaults have
    | been setup for each driver as an example of the required options.
    |
    | Supported Drivers: "local", "ftp", "sftp", "s3"
    |
    */

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ],

        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
        ],

        'disk1' => [
            'driver' => 'local',
            'root' => public_path() . '/disk1',
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        'disk2' => [
            'driver' => 'local',
            'root' => public_path() . '/disk2',
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        //disk3
        'disk3' => [
            'driver' => 'local',
            'root' => public_path() . '/disk3',
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],

        //disk4
        'disk4' => [
            'driver' => 'local',
            'root' => public_path() . '/disk4',
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        //disk6
        'disk6' => [
            'driver' => 'local',
            'root' => public_path() . '/disk6/disk6',
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        'disk7' => [
            'driver' => 'local',
            'root' => public_path() . '/disk7',
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],


        'uploads' => [
            'driver' => 'local',
            'root' => public_path() . '/uploads',
            'url' => env('APP_URL').'/storage',
            'visibility' => 'public',
        ],
        // 'google' => [
        //     'driver' => 'google',
        //     'clientId' => env("GOOGLE_CLIENT_ID"),
        //     'clientSecret' => env("GOOGLE_CLIENT_SECRET"),
        //     'refreshToken' => env("GOOGLE_REFRESH_TOKEN"),
        //     'folderId' => env("GOOGLE_DRIVE_FOLDER_ID"),
        // ],

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
