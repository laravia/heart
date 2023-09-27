<?php

namespace Laravia\Heart\App\Providers;

use Illuminate\Support\Facades\App;
use Laravia\Heart\App\Laravia;

class LaraviaServiceProvider extends ServiceProvider
{
    protected $name = 'heart';

    public function register()
    {
    }

    public function setMacros()
    {
    }

    public function boot()
    {
        $this->setMacros();

        $this->loadViewsFrom(Laravia::path()->get($this->name) . '/resources/views', $this->laravia);
        $this->loadTranslationsFrom(Laravia::path()->get($this->name) . '/lang', $this->laravia);
        $this->loadMigrationsFrom(Laravia::path()->get($this->name) . '/database/migrations');
        $this->loadSeedsFrom(Laravia::path()->get($this->name) . '/database/seeders');

        App::booted(function () {
            $path = Laravia::path()->get($this->name) . '/routes/web.php';
            $this->loadRoutesFrom($path);
        });

        foreach (Laravia::commands('heart') as $command) {
            $this->commands($command);
        }
    }
}
