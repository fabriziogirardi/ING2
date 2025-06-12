<?php

namespace App\Filament\Resources\FooterElementResource\Pages;

use App\Filament\Resources\FooterElementResource;
use Filament\Resources\Pages\CreateRecord;

class CreateFooterElement extends CreateRecord
{
    protected static string $resource = FooterElementResource::class;
    
    protected static bool $canCreateAnother = false;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
