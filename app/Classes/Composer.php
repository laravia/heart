<?php

namespace Laravia\Heart\App\Classes;

use Illuminate\Support\Facades\File;

class Composer
{
    public array $packages = [];

    public function parseIntoArray($key, $repository)
    {
        $packageFolderPath = base_path('vendor/' . $key);
        if (data_get($repository, 'laravia')) {
            $name = data_get($repository, 'name');
            $package['name'] = $name;
            $package['package'] = $key;
            $package['path'] = $packageFolderPath;
            $package['config'] = $packageFolderPath . '/config/' . $name . '.php';
            $package['routes']['backend'] = [];

            $routesFolder = $packageFolderPath . '/routes';
            if(File::exists($routesFolder)){
                foreach(File::allFiles($packageFolderPath . '/routes') as $route){
                    $key = $route->getFilenameWithoutExtension();
                    $filename = $route->getFilename();
                    $package['routes'][$key] = $packageFolderPath . '/routes/'.$filename;
                }
            }

            $dirs = array_filter(glob($packageFolderPath . '/lang/*'), 'is_dir');
            foreach ($dirs as $dir) {
                $lang = basename($dir);
                $package['lang'][$lang] = $packageFolderPath . '/lang/' . $lang . '/common.php';
            }
        }
        return $package;
    }
    public function parse(): void
    {
        $composerRepositories = file_get_contents(base_path('composer.json'));
        $composerRepositories = json_decode($composerRepositories, true);
        $composerRepositories = data_get($composerRepositories, 'repositories', []);

        foreach($composerRepositories as $key => $repoDetailsAsArray){
            $this->packages[str_replace('laravia/','',$key)] = $this->parseIntoArray($key,$repoDetailsAsArray);
        }
    }

    public function getPackage($name = ""): array
    {
        $this->parse();
        return data_get($this->packages, $name);
    }
    public function getPackages()
    {
        $this->parse();
        return $this->packages;
    }

    public function getFilesByKey($key): array
    {
        $this->parse();
        $files = [];
        foreach ($this->packages as $name => $package) {
            $files[$name] = data_get($package, $key);
        }
        return $files;
    }

    public function includeFileFromPackageByKeyAndLoadContentIntoArray($key): array
    {
        $fileContentAsArray = [];
        $config = [];
        foreach ($this->getFilesByKey($key) as $file) {
            if (File::exists($file)) {
                include $file;
                //this file requires a config array
                //after including once, the config array is available
                if (!empty($config)) {
                    $fileContentAsArray[key($config)] = $config[key($config)];
                }
            }
        }
        return $fileContentAsArray;
    }

    public function getValueFromConfigArrayByKey($key): string|null|array
    {
        $config = $this->includeFileFromPackageByKeyAndLoadContentIntoArray('config');
        return data_get($config, $key);
    }
}
