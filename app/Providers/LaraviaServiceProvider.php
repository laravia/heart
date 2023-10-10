<?php

namespace Laravia\Heart\App\Providers;

use Laravia\Heart\App\Laravia;
use Orchid\Platform\Dashboard;
use Orchid\Screen\Actions\Menu;

class LaraviaServiceProvider extends ServiceProvider
{
    protected $name = 'heart';

    public function menu(): array
    {
        //will be refactored with dynamic menu items
        return [
            Menu::make(__('Dashboard'))
                ->icon('bs.heart')
                ->title(__('System'))
                ->route('laravia.heart')
                ->divider(),


            //default orchid menu items
            Menu::make(__('Users'))
                ->icon('bs.people')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Access Controls')),
            Menu::make(__('Roles'))
                ->icon('bs.shield')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles')
                ->divider(),
        ];
    }

    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);
        $this->defaultBootMethod();

        $this->overwriteOrchidSettings();

        foreach (Laravia::commands('heart') as $command) {
            $this->commands($command);
        }
    }

    public function overwriteOrchidSettings(): void
    {
        $this->app['config']->set('platform.index', 'laravia.heart');
    }
}
