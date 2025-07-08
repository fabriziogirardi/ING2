<?php

namespace App\Filament\Resources\ProductStatisticsResource\Pages;

use App\Filament\Resources\ProductStatisticsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductStatistics extends EditRecord
{
    protected static string $resource = ProductStatisticsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
