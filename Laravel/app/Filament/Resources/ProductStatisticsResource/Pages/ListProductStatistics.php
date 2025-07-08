<?php

namespace App\Filament\Resources\ProductStatisticsResource\Pages;

use App\Filament\Resources\ProductStatisticsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListProductStatistics extends ListRecords
{
    protected static string $resource = ProductStatisticsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
