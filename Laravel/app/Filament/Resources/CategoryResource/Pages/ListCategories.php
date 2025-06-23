<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCategories extends ListRecords
{
    protected static string $resource = CategoryResource::class;
    
    public function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Agregar nueva categoría')
                ->icon('heroicon-o-plus')
                ->createAnother(false),
        ];
    }
}
