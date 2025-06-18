<?php

namespace App\Filament\Resources\ForumSectionResource\Pages;

use App\Filament\Resources\ForumSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateForumSection extends CreateRecord
{
    protected static string $resource = ForumSectionResource::class;

    protected static bool $canCreateAnother = false;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Crear sección')
                ->successNotificationTitle(__('manager/section.created')),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return ForumSectionResource::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): string
    {
        return __('manager/section.created');
    }

    public function getTitle(): string
    {
        return 'Crear sección del foro';
    }
}
