<?php

namespace App\Filament\Resources\ProductBrandResource\Pages;

use App\Filament\Resources\ProductBrandResource;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;

class ListProductBrands extends ListRecords
{
    protected static string $resource = ProductBrandResource::class;

    protected static ?string $title = 'Marcas de productos';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->createAnother(false)
                ->label('Agregar marca')
                ->name('Agregar marca')
                ->form([
                    TextInput::make('name')
                        ->label('Nombre de la marca')
                        ->required()
                        ->unique(ProductBrandResource::getModel(), 'name')
                        ->maxLength(255),
                ]),
        ];
    }
}
