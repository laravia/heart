<?php

namespace Laravia\Heart\App\Classes;

use Illuminate\Support\Facades\File;

class Composer
{

    public array $packages = [];

    public function parse(): void
    {
        $composerRepositories = file_get_contents(base_path('composer.json'));
        $composerRepositories = json_decode($composerRepositories, true);
        $composerRepositories = data_get($composerRepositories, 'repositories', []);

        foreach ($composerRepositories as $key => $repository) {
            $packageFolderPath = base_path('vendor/' . $key);
            if (data_get($repository, 'laravia')) {
                $name = data_get($repository, 'name');
                $packages[$name]['name'] = $name;
                $packages[$name]['package'] = $key;
                $packages[$name]['path'] = $packageFolderPath;
                $packages[$name]['config'] = $packageFolderPath . '/config/' . $name . '.php';
                $packages[$name]['routes']['web'] = [];
                if (File::exists($packageFolderPath . 'routes/web.php')) {
                    $packages[$name]['routes']['web'] = $packageFolderPath . '/routes/web.php';
                }
                // $packages[$name]['lang']['en'] = $packageFolderPath . '/lang/en/common.php';
                // $packages[$name]['lang']['de'] = $packageFolderPath . '/lang/de/common.php';
                // $packages[$name]['lang']['es'] = $packageFolderPath . '/lang/es/common.php';
            }
        }

        $this->packages = $packages;
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
