<?php

use Carbon\Carbon;
use Laravia\Heart\App\Classes\Embed;
use Laravia\Heart\App\Laravia;

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
        'title' => __('Heart'),
        'route' => 'laravia.heart',
        'sort' => 1
    ]
];

$config['heart']['commands'] = [
    'Laravia\Heart\App\Console\Commands\Laravia',
    'Laravia\Heart\App\Console\Commands\Call',
    'Laravia\Heart\App\Console\Commands\Publish',
    'Laravia\Heart\App\Console\Commands\PackageCloneWithSearchAndReplace',
];

$config['heart']['dashboard']['metrics'] = [
    'laravia' => ['sort' => 1, 'value' => $config['heart']['name'] . " " . $config['heart']['version'], 'title' => 'Laravia Version'],
    'users' => ['sort' => 20, 'value' => Laravia::getDashboardMetrics('users'), 'title' => 'Users Count'],
];

$config['heart']['publish'] = [
];

$config['heart']['call'] = [
    'php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider"'
];

$config['heart']['parsers'] = [
    ['time:now', Carbon::now()->format(data_get($config, 'core.timeFormat'))],
    ['datetime:now', Carbon::now()->format(data_get($config, 'core.dateTimeFormat'))],
    ['date:now', Carbon::now()->format(data_get($config, 'core.dateFormat'))],
    ['date:yesterday', Carbon::now()->subDay()->format(data_get($config, 'core.dateFormat'))],
    ['date:tomorrow', Carbon::now()->addDay()->format(data_get($config, 'core.dateFormat'))],
    ['date:year:now', Carbon::now()->format('Y')],
    ['date:year:next', Carbon::now()->addYear()->format('Y')],
    ['icon', '', '', true],
    ['youtube', '', '', true, Embed::class],
];
