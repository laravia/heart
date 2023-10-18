<?php

namespace Laravia\Heart\Tests\Unit\Classes;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Laravia\Heart\App\Classes\TestCase;
use Laravia\Heart\App\Console\Commands\PackageCloneWithSearchAndReplace;

class PackageCloneWithSearchAndReplaceTest extends TestCase
{
    protected string $message = 'PackageCloneWithSearchAndReplace = done! all cloned into: ';
    public $class = PackageCloneWithSearchAndReplace::class;

    public function testInitClass()
    {
        $this->assertClassExist($this->class);
    }

    public function testRunCommand()
    {
        Artisan::shouldReceive('call')
            ->once()
            ->with('laravia:package:clone vendor/laravia/heart heart core')
            ->andReturn($this->message . "/var/www/html/storage/framework/tmp/vendor/laravia/core");

        Artisan::call('laravia:package:clone vendor/laravia/heart heart core');
    }

    public function testRunCommandTestExcludeFolders()
    {
        $path = base_path('storage/framework/tmp/vendor/laravia/core');
        $this->artisan('laravia:package:clone vendor/laravia/heart heart core')
            ->expectsOutput($this->message . "/var/www/html/storage/framework/tmp/vendor/laravia/core")
            ->assertExitCode(0);

        $this->assertDirectoryExists($path);

        $allFiles = [];
        foreach (File::allFiles($path, true) as $file) {
            $allFiles[] = $file->getBasename();
        }
        foreach (File::directories($path, true) as $file) {
            $allFiles[] = $file;
        }

        $command = new $this->class();
        $this->assertNotContains($command->removeFilesOrFolderAfterClone, $allFiles);
        File::deleteDirectory($path);
        $this->assertDirectoryDoesNotExist($path);
    }

    public function testRunCommandTestCopyPasteToDifferentFolder()
    {
        $outputFolder = 'storage/framework/tmp/vendor/laravia/test';
        $this->artisan('laravia:package:clone vendor/laravia/heart heart core ' . $outputFolder)
            ->expectsOutput($this->message . $outputFolder)
            ->assertExitCode(0);

        $this->assertDirectoryExists($outputFolder);

        File::deleteDirectory($outputFolder);
        $this->assertDirectoryDoesNotExist($outputFolder);
    }
}
