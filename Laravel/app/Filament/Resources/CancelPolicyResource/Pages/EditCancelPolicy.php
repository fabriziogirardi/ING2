<?php

namespace App\Filament\Resources\CancelPolicyResource\Pages;

use App\Filament\Resources\CancelPolicyResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCancelPolicy extends EditRecord
{
    protected static string $resource = CancelPolicyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
