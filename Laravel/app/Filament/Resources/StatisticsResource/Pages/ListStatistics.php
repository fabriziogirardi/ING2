<?php

namespace App\Filament\Resources\StatisticsResource\Pages;

use App\Filament\Resources\StatisticsResource;
use Filament\Actions;
use Filament\Pages\Concerns\ExposesTableToWidgets;
use Filament\Resources\Pages\ListRecords;

class ListStatistics extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = StatisticsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatisticsResource\Widgets\StatisticsOverview::class,
        ];
    }
}
