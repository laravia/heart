<?php

namespace Laravia\Heart\App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Publish extends Command
{

    public $signature = 'laravia:publish {force? : force? default value is false}';
    public $force = false;
    protected $description = 'laravia publish (all needed external providers)';

    public function publishProviders()
    {
        foreach (\Laravia::config('*.publish') as $provider) {
            if (is_array($provider)) {
                foreach ($provider as $value) {
                    $this->line('publishing ' . $value);
                    Artisan::call('vendor:publish', [
                        '--provider' => $value,
                        '--force' => $this->force
                    ]);
                }
            }
        }
    }

    public function handle()
    {
        $this->force = $this->argument('force') ? true : false;

        $this->publishProviders();

        $this->line('all files published');
    }
}
