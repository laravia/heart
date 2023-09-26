<?php

namespace Laravia\Heart\App\Console\Commands;

use Illuminate\Console\Command;
use Laravia\Heart\App\Laravia as LaraviaHeart;

class Laravia extends Command
{

    public $signature = 'laravia';

    protected $description = 'output infos for laravia';

    public function handle()
    {
        $this->line('');
        $this->line('############## LARAVIA ################');
        $this->line('#');
        $this->line('# '.LaraviaHeart::config('heart.name') . " " . LaraviaHeart::config('heart.version'));
        $this->line('# '.LaraviaHeart::config('heart.github'));
        $this->line('# '.LaraviaHeart::config('heart.homepage'));
        $this->line('#');
        $this->line('############### COMMANDS ##############');
        $this->line('#');
        foreach(LaraviaHeart::commands() as $commandModel){
            $this->line('# php artisan '.app($commandModel)->signature);
        }
        $this->line('#');
        $this->line('#######################################');
        $this->line('');
    }
}
