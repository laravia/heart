<?php

namespace Laravia\Heart\App\Providers;

use App\Orchid\PlatformProvider;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Laravia\Heart\App\Laravia;
use Symfony\Component\Console\Output\ConsoleOutput;

class ServiceProvider extends PlatformProvider
{

    protected $laravia = "laravia";
    protected $name = "heart";

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
        $this->loadMigrationsFrom(Laravia::path()->get($this->name) . '/database/migrations');
        $this->loadSeedsFrom(Laravia::path()->get($this->name) . '/database/seeders');

        App::booted(function () {
            foreach(File::allFiles(Laravia::path()->get($this->name) . '/routes') as $route){
                $this->loadRoutesFrom($route);
            }
        });

        $this->publishes([
            Laravia::path()->get($this->name) . "/public" => public_path('vendor'),
        ], $this->name);
    }

}
