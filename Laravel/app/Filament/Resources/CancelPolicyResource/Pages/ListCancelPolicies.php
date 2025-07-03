<?php

namespace App\Filament\Resources\CancelPolicyResource\Pages;

use App\Filament\Resources\CancelPolicyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCancelPolicies extends ListRecords
{
    protected static string $resource = CancelPolicyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
