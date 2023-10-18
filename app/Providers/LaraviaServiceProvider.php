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
        foreach (Laravia::links() as $linkData) {

            $link = Menu::make(data_get($linkData, 'name'))
                ->icon(data_get($linkData, 'icon'))
                ->title(data_get($linkData, 'title'))
                ->active(data_get($linkData, 'active'));

            if ($route = data_get($linkData, 'route')) {
                $link->route($route);
            }

            if ($url = data_get($linkData, 'url')) {
                $link->url($url);
            }

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

        $this->addLaraConfigToLaravelConfig();
        $this->overwriteOrchidSettings();
    }

    public function addLaraConfigToLaravelConfig(): void
    {
        $this->app['config']->set('laravia', Laravia::config());
    }

    public function overwriteOrchidSettings(): void
    {
        $this->app['config']->set('platform.index', 'laravia.heart');
    }
}
