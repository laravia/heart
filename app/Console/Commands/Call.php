<?php

namespace Laravia\Heart\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Laravia\Heart\App\Laravia;

class Call extends Command
{

    public $signature = 'laravia:call';
    protected $description = 'laravia call (run alle call config commands)';

    public function install()
    {
        foreach (Laravia::config('*.call') as $install) {
            if (is_array($install)) {
                foreach ($install as $installcode) {
                    $this->line('Artisan::call(' . $installcode.')');
                    $this->call($installcode);
                }
            }
        }
    }

    public function handle()
    {
        $this->install();
        $this->line('all commands called');
    }
}
