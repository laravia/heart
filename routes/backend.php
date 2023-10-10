<?php

use Illuminate\Support\Facades\Route;
use Laravia\Heart\App\Orchid\Screens\Dashboard;
use Tabuna\Breadcrumbs\Trail;

$prefix = config('platform.prefix');

Route::middleware(['web', 'auth', 'platform'])->group(function () use ($prefix) {

    Route::screen($prefix . '/dashboard', Dashboard::class)
        ->name('laravia.heart')
        ->breadcrumbs(function (Trail $trail) {
            return $trail
                ->parent('platform.index')
                ->push('Dashboard');
        });
});
