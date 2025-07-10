<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Actions\Action;
use Filament\Forms\Get;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;

    protected static bool $canCreateAnother = false;

    protected function getCreateFormAction(): Action
    {
        return parent::getCreateFormAction()
            ->disabled(function (Get $get): bool {
                $emailSearch    = $get('email_search');
                $relationExists = $get('relation_exists');

                // Deshabilitar si:
                // 1. No hay email ingresado
                // 2. Ya existe la relación (persona ya es empleado)
                return empty($emailSearch) || $relationExists === true;
            });
    }
}
