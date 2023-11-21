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
        $this->line('# ' . LaraviaHeart::config('heart.name') . " " . LaraviaHeart::config('heart.version'));
        $this->line('# ' . LaraviaHeart::config('heart.github'));
        $this->line('# ' . LaraviaHeart::config('heart.homepage'));
        $this->line('#');
        $this->line('############### COMMANDS ##############');
        $this->line('#');
        foreach (LaraviaHeart::commands() as $command) {
            $this->line('# php artisan ' . app($command)->signature);
        }
        $this->line('#');
        $this->line('#######################################');
        $this->line('############### SCHEDULES ##############');
        $this->line('#');
        foreach (LaraviaHeart::schedules() as $schedule) {
            $this->line('# ' .
                data_get($schedule, 0) . " " .
                ' | ' .
                data_get($schedule, 1) .
                ' | local: ' .
                (data_get($schedule, 2) ? __('laravia.heart::common.yes') :  __('laravia.heart::common.no')));
        }
        $this->line('#');
        $this->line('#######################################');
        $this->line('');
    }
}
