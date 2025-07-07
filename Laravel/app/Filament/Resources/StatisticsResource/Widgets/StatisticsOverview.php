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

                // Subtítulo con totales
                'subtitle' => [
                    'display' => true,
                    'text'    => sprintf(
                        'Ventas: $%s   |   Devoluciones: $%s | Ganancia Total: $%s',
                        number_format($totals['revenue'], 0, ',', '.'),
                        number_format($totals['refunds'], 0, ',', '.'),
                        number_format($totals['net_revenue'], 0, ',', '.')
                    ),
                    'font'     => ['size' => 24],
                    'position' => 'bottom',
                    'padding'  => ['top' => 6],
                ],
            ],

            'maintainAspectRatio' => false,
            'responsive'          => true,
        ];
    }
}
