<?php

namespace Laravia\Heart\App\Orchid\Screens;

use App\Models\User;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class Dashboard extends Screen
{
    public function query(): iterable
    {
        return [
            'metrics' => [
                'users'    => ['value' => User::count()],
            ],
        ];
    }

    public function name(): ?string
    {
        return 'Dashboard Screen';
    }

    public function description(): ?string
    {
        return 'The Dashboard of Laravia';
    }

    public function commandBar(): iterable
    {
        return [];
    }

    public function layout(): iterable
    {
        return [

            Layout::metrics([
                'Users'    => 'metrics.users',
            ]),

        ];
    }
}
