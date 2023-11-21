<?php

namespace Laravia\Heart\App\Traits;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Laravia\Heart\App\Laravia;
use Symfony\Component\Console\Output\ConsoleOutput;

trait ServiceProviderTrait
{
    public function getPackagePrefix()
    {
        return $this->getPackageRoot() . '.' . $this->name;
    }

    public function getPackageRoot()
    {
        if (empty($this->packageRoot)) {
            return 'laravia';
        } else {
            return $this->packageRoot;
        }
    }
    protected function defaultBootMethod()
    {
        $this->loadViews();
        $this->loadTranslations();
        $this->loadMigrations();
        $this->loadSeeders();
        $this->loadPublishes();
        $this->loadCommands();

        App::booted(function () {
            $this->loadRoutes();
            $this->loadSchedules();
        });
    }

    protected function loadViews()
    {
        try {
            $this->loadViewsFrom(Laravia::path()->get($this->name) . '/resources/views', $this->getPackagePrefix());
        } catch (\Throwable $th) {
            Log::error($th);
        }
    }

    protected function loadTranslations()
    {
        try {
            $this->loadTranslationsFrom(Laravia::path()->get($this->name) . '/lang', $this->getPackagePrefix());
        } catch (\Throwable $th) {
            Log::error($th);
        }
    }

    protected function loadMigrations()
    {
        try {
            $migrationsPath = Laravia::path()->get($this->name) . '/database/migrations';
            if (File::exists($migrationsPath) && sizeof(File::allFiles($migrationsPath))) {
                $this->loadMigrationsFrom($migrationsPath);
            }
        } catch (\Throwable $th) {
            Log::error($th);
        }
    }

    protected function loadSeeders()
    {
        try {
            $seedersPath = Laravia::path()->get($this->name) . '/database/seeders';
            if (File::exists($seedersPath) && sizeof(File::allFiles($seedersPath))) {
                $this->loadSeedsFrom($seedersPath);
            }
        } catch (\Throwable $th) {
            Log::error($th);
        }
    }

    protected function loadSeedsFrom($path)
    {
        $this->callAfterResolving(DatabaseSeeder::class, function ($seeder) use ($path) {

            foreach (File::allFiles($path) as $filename) {

                $namespace = 'Laravia\\' . ucfirst($this->name) . '\\Database\\Seeders\\';
                $class = $namespace . $filename->getFilenameWithoutExtension();

                $seeder->call($class);
                $info = $class . " was successfully seeded";
                $output = new ConsoleOutput();
                $output->writeln("<info>" . $info . "</info>");
            }
        });
    }
    protected function loadPublishes()
    {
        try {
            $this->publishes([
                Laravia::path()->get($this->name) . "/protected" => public_path('vendor'),
            ], $this->getPackagePrefix());
        } catch (\Throwable $th) {
            Log::error($th);
        }
    }

    protected function loadCommands()
    {
        try {
            if ($commands = Laravia::config($this->name . '.commands')) {
                foreach ($commands as $command) {
                    $this->commands($command);
                }
            }
        } catch (\Throwable $th) {
            Log::error($th);
        }
    }

    protected function loadRoutes()
    {
        try {
            $routesPath = Laravia::path()->get($this->name) . '/routes';
            if (File::exists($routesPath) && sizeof(File::allFiles($routesPath))) {
                foreach (File::allFiles($routesPath) as $route) {
                    $this->loadRoutesFrom($route);
                }
            }
        } catch (\Throwable $th) {
            Log::error($th);
        }
    }

    protected function loadSchedules()
    {
        try {
            $appSchedule = $this->app->make(Schedule::class);
            if ($schedules = Laravia::config($this->name . '.schedules')) {
                foreach ($schedules as $schedule) {

                    $command = data_get($schedule, 0);
                    $cron = data_get($schedule, 1);
                    $localExecutionPermitted = data_get($schedule, 2);

                    if (Laravia::config('app.production') || $localExecutionPermitted) {
                        $appSchedule->command($command)->cron($cron);
                    }
                }
            }
        } catch (\Throwable $th) {
            Log::error($th);
        }
    }
}
