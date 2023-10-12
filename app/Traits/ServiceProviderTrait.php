<?php

namespace Laravia\Heart\App\Traits;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Laravia\Heart\App\Laravia;
use Symfony\Component\Console\Output\ConsoleOutput;

trait ServiceProviderTrait
{
    protected $laravia = "laravia";

    public function getPackagePrefix()
    {
        return $this->laravia . '.' . $this->name;
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
        $this->loadViewsFrom(Laravia::path()->get($this->name) . '/resources/views', $this->getPackagePrefix());

        $migrationsPath = Laravia::path()->get($this->name) . '/database/migrations';
        if (File::exists($migrationsPath)) {
            $this->loadMigrationsFrom($migrationsPath);
        }
        $seedersPath = Laravia::path()->get($this->name) . '/database/seeders';
        if (File::exists($seedersPath)) {
            $this->loadSeedsFrom($seedersPath);
        }

        App::booted(function () {
            $routesPath = Laravia::path()->get($this->name) . '/routes';
            if (File::exists($routesPath)) {
                foreach (File::allFiles($routesPath) as $route) {
                    $this->loadRoutesFrom($route);
                }
            }
        });

        $this->publishes([
            Laravia::path()->get($this->name) . "/public" => public_path('vendor'),
        ], $this->name);

        foreach (Laravia::commands($this->name) as $command) {
            $this->commands($command);
        }
    }
}
