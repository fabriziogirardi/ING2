<?php

namespace App\Filament\Resources\ForumSectionResource\Pages;

use App\Filament\Resources\ForumSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditForumSection extends EditRecord
{
    protected static string $resource = ForumSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string
    {
        return 'Editar sección del foro';
    }
}
