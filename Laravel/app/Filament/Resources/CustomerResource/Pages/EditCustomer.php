<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCustomer extends EditRecord
{
    protected static string $resource = CustomerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->label('Bloquear cuenta')
                ->icon('heroicon-o-lock-closed')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('¿Estás seguro de que querés bloquear esta cuenta?')
                ->modalDescription('Esta acción impedirá el acceso del usuario hasta que se desbloquee.')
                ->modalSubmitActionLabel('Sí, bloquear cuenta'),
            Actions\RestoreAction::make()
                ->label('Reanudar cuenta')
                ->icon('heroicon-o-lock-open')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('¿Querés reanudar el acceso a esta cuenta?')
                ->modalDescription('El usuario podrá volver a iniciar sesión normalmente.')
                ->modalSubmitActionLabel('Sí, reanudar cuenta'),
        ];
    }
}
