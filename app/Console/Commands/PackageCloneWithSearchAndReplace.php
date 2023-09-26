<?php

namespace Laravia\Heart\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class PackageCloneWithSearchAndReplace extends Command
{

    public $signature = 'laravia:package:clone {inputFolder : the input folder} {search : the search key} {replace : the replace key} {outputFolder? : the output folder}';

    protected $description = 'clone a package with search and replace. required: inputfolder, search value and replace value ';

    protected string $search;
    protected string $replace;
    protected string $relativeInputFolder;
    protected string $inputFolder;
    protected string|null $outputFolder;
    protected string $storagePath;
    protected array $allFilesFromOutputFolder;

    public function handle()
    {
        $this->setParameters();

        $this->createTempFolderIfNotExists();

        $this->checkIfInputFolderExists();

        $this->deleteOutputFolderIfExists();

        $this->copyAndPasteFolder();

        $this->setArrayWithAllFilesFromOutputFolder();

        $this->renameFolder();

        $this->renameFiles();

        $this->rewriteFiles();

        $this->line(class_basename($this) . ' = done! all cloned into: '.$this->outputFolder);
    }

    protected function setParameters()
    {
        $this->search = $this->argument('search');
        $this->replace = $this->argument('replace');
        $this->storagePath = storage_path('framework/tmp/');
        $this->relativeInputFolder = $this->argument('inputFolder');
        $this->inputFolder = base_path($this->relativeInputFolder);
        $this->outputFolder = $this->argument('outputFolder') ?: $this->storagePath . $this->relativeInputFolder;
    }

    protected function createTempFolderIfNotExists()
    {
        if (!File::exists($this->storagePath)) {
            if (!File::makeDirectory($this->storagePath)) {
                $this->abortWithErrorMessage('temp folder can\t be created');
            }
        }
        return true;
    }

    protected function checkIfInputFolderExists()
    {
        if (!File::exists($this->inputFolder)) {
            $this->abortWithErrorMessage('input folder do not exists');
        }
        return true;
    }

    protected function deleteOutputFolderIfExists()
    {
        if (File::exists($this->outputFolder)) {
            File::deleteDirectory($this->outputFolder);
        }
    }

    protected function copyAndPasteFolder()
    {
        if (!File::copyDirectory($this->inputFolder, $this->outputFolder)) {
            $this->abortWithErrorMessage('folder can\'t be copied');
        }
        return true;
    }

    protected function abortWithErrorMessage($message = "")
    {
        $this->error($message);
        exit;
    }

    protected function setArrayWithAllFilesFromOutputFolder()
    {
        $this->allFilesFromOutputFolder = File::allFiles($this->outputFolder);
    }

    protected function renameFolder()
    {
        $originalFolderName = $this->outputFolder;
        $this->outputFolder = Str::replaceLast($this->search, $this->replace, $this->outputFolder);
        $this->deleteOutputFolderIfExists();
        File::moveDirectory($originalFolderName, $this->outputFolder);
        $this->setArrayWithAllFilesFromOutputFolder();
    }

    protected function renameFiles()
    {
        foreach ($this->allFilesFromOutputFolder as $file) {
            if ($newPath = preg_replace('/' . ucfirst($this->search) . '/', ucfirst($this->replace), $file)) {
                File::move($file->getPathName(), $newPath);
            }
        }
        $this->setArrayWithAllFilesFromOutputFolder();
        foreach ($this->allFilesFromOutputFolder as $file) {
            if ($newPath = preg_replace('/' . $this->search . '/', $this->replace, $file)) {
                File::move($file->getPathName(), $newPath);
            }
        }
        $this->setArrayWithAllFilesFromOutputFolder();
    }

    public function rewriteFiles()
    {
        foreach ($this->allFilesFromOutputFolder as $file) {
            $fileContent = File::get($file);
            $fileContent = str_replace(ucfirst($this->search), ucfirst($this->replace), $fileContent);
            $fileContent = str_replace($this->search, $this->replace, $fileContent);
            $fileContent = str_replace(strtoupper($this->search), strtoupper($this->replace), $fileContent);
            File::put($file, $fileContent);
        }
    }
}
