<?php

use Orchid\Screen\Actions\Menu;

$config['heart'] = [
    'name' => 'Laravia',
    'version' => '0.2',
    'github' => 'https://github.com/laravia',
    'homepage' => 'http://laravia.art',
    'dateFormat' => 'd.m.Y',
    'timeFormat' => 'H:i:s',
    'dateTimeFormat' => 'd.m.Y H:i:s',
    'emailRecipient' => env('MAIL_DEFAULT_RECIPIENT'),
    'emailSender' => env('MAIL_FROM_ADDRESS')
];

$config['heart']['links'] = [
    [
        'name' => __('Dashboard'),
        'icon' => 'bs.heart',
        'title' => __('System'),
        'route' => 'laravia.heart',
    ]
];

$config['heart']['commands'] = [
    'Laravia\Heart\App\Console\Commands\Laravia',
    'Laravia\Heart\App\Console\Commands\Call',
    'Laravia\Heart\App\Console\Commands\Publish',
    'Laravia\Heart\App\Console\Commands\PackageCloneWithSearchAndReplace',
];

$config['heart']['publish'] = [
    'Spatie\Backup\BackupServiceProvider',
];

$config['heart']['call'] = [
    'orchid:install',
    'orchid:admin admin admin@admin.com password --create',
];
