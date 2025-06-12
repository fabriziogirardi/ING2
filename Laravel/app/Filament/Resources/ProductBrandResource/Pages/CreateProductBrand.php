<?php

namespace App\Filament\Resources\ProductBrandResource\Pages;

use App\Filament\Resources\ProductBrandResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProductBrand extends CreateRecord
{
    protected static string $resource = ProductBrandResource::class;

    protected static bool $canCreateAnother = false;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
