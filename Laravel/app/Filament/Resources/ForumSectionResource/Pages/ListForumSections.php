<?php

namespace App\Filament\Resources\ForumSectionResource\Pages;

use App\Filament\Resources\ForumSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListForumSections extends ListRecords
{
    protected static string $resource = ForumSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Crear sección'),
        ];
    }
}
