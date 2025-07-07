<?php

namespace App\Filament\Resources\StatisticsResource\Widgets;

use App\Filament\Resources\StatisticsResource;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\Concerns\InteractsWithPageTable;

class StatisticsOverview extends ChartWidget
{
    use InteractsWithPageTable;

    protected static ?string $heading = 'Gráfico de ganancias/devoluciones';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $filters   = $this->tableFilters;
        $dateRange = $filters['date_range'] ?? [];
        $start     = $dateRange['start_date'] ?? null;
        $end       = $dateRange['end_date'] ?? null;
        $branches  = $filters['branch']['values'] ?? [];

        return StatisticsResource::getChartData($start, $end, $branches);
    }

    protected function getOptions(): array
    {
        $totals = $this->getData()['totals'];

        return [
            'cutout' => '50%',

            'plugins' => [
                'legend' => [
                    'display'  => true,
                    'position' => 'bottom',
                    'labels'   => [
                        'padding' => 20,
                        'font'    => ['size' => 14],
                    ],
                ],
            ],

            'maintainAspectRatio' => false,
            'responsive'          => true,
        ];
    }
}
