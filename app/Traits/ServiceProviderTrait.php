<?php

namespace Laravia\Heart\App\Traits;

use Database\Seeders\DatabaseSeeder;
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

    protected function defaultBootMethod()
    {

        try {
            $this->loadViewsFrom(Laravia::path()->get($this->name) . '/resources/views', $this->getPackagePrefix());
        } catch (\Throwable $th) {
            Log::error($th);
        }

        try {
            $this->loadTranslationsFrom(Laravia::path()->get($this->name) . '/lang', $this->getPackagePrefix());
        } catch (\Throwable $th) {
            Log::error($th);
        }

        try {
            $migrationsPath = Laravia::path()->get($this->name) . '/database/migrations';
            if (File::exists($migrationsPath) && sizeof(File::allFiles($migrationsPath))) {
                $this->loadMigrationsFrom($migrationsPath);
            }
        } catch (\Throwable $th) {
            Log::error($th);
        }

        try {
            $seedersPath = Laravia::path()->get($this->name) . '/database/seeders';
            if (File::exists($seedersPath) && sizeof(File::allFiles($seedersPath))) {
                $this->loadSeedsFrom($seedersPath);
            }
        } catch (\Throwable $th) {
            Log::error($th);
        }


        App::booted(function () {
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
        });

        try {
            $this->publishes([
                Laravia::path()->get($this->name) . "/public" => public_path('vendor'),
            ], $this->getPackagePrefix());
        } catch (\Throwable $th) {
            Log::error($th);
        }

        try {
            foreach (Laravia::commands($this->name) as $command) {
                $this->commands($command);
            }
        } catch (\Throwable $th) {
            Log::error($th);
        }
    }
}
