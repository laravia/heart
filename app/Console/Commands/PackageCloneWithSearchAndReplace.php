<?php

namespace Laravia\Heart\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


class PackageCloneWithSearchAndReplace extends Command
{

    public $signature = 'laravia:package:clone {inputFolder : the input folder} {search : the search key} {replace : the replace key} {outputFolder? : the output folder}';

    protected $description = 'clone a package with search and replace. required: inputfolder, search value and replace value ';

    public array $removeFilesOrFolderAfterClone = ['.git'];
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

        $this->createTempFolderIfNotExists($this->storagePath);

        $this->checkIfInputFolderExists();

        $this->deleteOutputFolderIfExists();

        $this->copyAndPasteFolder();

        $this->setArrayWithAllFilesFromOutputFolder();

        $this->removeFilesOrFolderAfterClone();

        $this->renameFolder();

        $this->renameFiles();

        $this->rewriteFiles();

        $this->copyAndPasteFolderToOutputFolder();

        $this->terminalOutput();
    }

    protected function setParameters()
    {
        $this->search = $this->argument('search');
        $this->replace = $this->argument('replace');
        $this->storagePath = storage_path('framework/tmp/');
        $this->relativeInputFolder = $this->argument('inputFolder');
        $this->inputFolder = base_path($this->relativeInputFolder);
        $this->outputFolder = $this->storagePath . $this->relativeInputFolder;
    }

    protected function createTempFolderIfNotExists($path)
    {
        if (!File::exists($path)) {
            if (!File::makeDirectory($path)) {
                $this->abortWithErrorMessage('temp folder ' . $path . ' can\t be created');
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

    protected function removeFilesOrFolderAfterClone()
    {
        foreach ($this->removeFilesOrFolderAfterClone as $fileOrFolder) {
            if (File::exists($this->outputFolder . '/' . $fileOrFolder)) {
                File::deleteDirectory($this->outputFolder . '/' . $fileOrFolder);
            }
        }
        return true;
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

    protected function copyAndPasteFolderToOutputFolder()
    {
        if ($this->argument('outputFolder')) {
            if (!File::copyDirectory($this->outputFolder, $this->argument('outputFolder'))) {
                $this->abortWithErrorMessage('folder can\'t be copied');
            } else {
                File::deleteDirectory($this->outputFolder);
            }
        }
        return true;
    }

    protected function terminalOutput()
    {
        $message = class_basename($this) . ' = done! all cloned into: ';
        if ($folder = $this->argument('outputFolder')) {
            $this->line($message . $folder);
        } else {
            $this->line($message . $this->outputFolder);
        }
    }
}
