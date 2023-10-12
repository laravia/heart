<?php

namespace Laravia\Heart\App\Providers;

use App\Orchid\PlatformProvider;
use Laravia\Heart\App\Laravia;
use Laravia\Heart\App\Traits\ServiceProviderTrait;
use Orchid\Platform\Dashboard;
use Orchid\Screen\Actions\Menu;

class LaraviaServiceProvider extends PlatformProvider
{
    use ServiceProviderTrait;
    protected $name = 'heart';
    protected $links = [];

    public function getAllLinksFromPackages(): void
    {
        $countLinks = count(Laravia::links());
        $i = 1;
        foreach (Laravia::links() as $link) {

            $link = Menu::make(data_get($link, 'name'))
                ->icon(data_get($link, 'icon'))
                ->title(data_get($link, 'title'))
                ->route(data_get($link, 'route'))
                ->active(data_get($link, 'active'));
            if ($i == $countLinks) {
                $link->divider();
            }
            $this->links[] = $link;
            $i++;
        }
    }

    public function getOrchidLinks(): void
    {
        $this->links[] = Menu::make(__('Users'))
            ->icon('bs.people')
            ->route('platform.systems.users')
            ->permission('platform.systems.users')
            ->title(__('Access Controls'));
        $this->links[] =    Menu::make(__('Roles'))
            ->icon('bs.shield')
            ->route('platform.systems.roles')
            ->permission('platform.systems.roles')
            ->divider();
    }

    public function menu(): array
    {
        $this->getAllLinksFromPackages();
        $this->getOrchidLinks();
        return $this->links;
    }

    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);
        $this->defaultBootMethod();

        $this->overwriteOrchidSettings();
    }

    public function overwriteOrchidSettings(): void
    {
        $this->app['config']->set('platform.index', 'laravia.heart');
    }
}
