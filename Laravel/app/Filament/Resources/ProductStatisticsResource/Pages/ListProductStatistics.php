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
            Actions\Action::make('reload')
                ->label('Aplicar filtros')
                ->color('gray')
                ->button()
                ->action(fn () => null)
                ->extraAttributes([
                    'x-on:click' => 'window.location.reload()',
                ]),
        ];
    }
}
