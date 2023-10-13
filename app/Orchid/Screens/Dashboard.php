<?php

namespace Laravia\Heart\App\Orchid\Screens;

use Illuminate\Http\Request;
use Laravia\Heart\App\Laravia;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;

class Dashboard extends Screen
{

    public function __invoke(Request $request, ...$arguments)
    {
        return $this->handle($request, ...$arguments);
    }

    public function query(): iterable
    {
        return [
            'metrics' => Laravia::dashboardMetrics(),
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

        $metricsArray = [];
        foreach(Laravia::dashboardMetrics() as $key=>$metrics){
            $metricsArray[data_get($metrics, 'title')] = 'metrics.'.$key;
        }

        return [

            Layout::metrics($metricsArray),

        ];
    }
}
