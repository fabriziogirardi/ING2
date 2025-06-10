<?php

namespace App\Filament\Resources\ProductBrandResource\Pages;

use App\Filament\Resources\ProductBrandResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProductBrand extends EditRecord
{
    protected static string $resource = ProductBrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
