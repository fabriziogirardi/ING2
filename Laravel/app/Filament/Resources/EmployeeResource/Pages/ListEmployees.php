<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Forms\PersonAdvancedForm;
use App\Filament\Resources\EmployeeResource;
use App\Models\Employee;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Hash;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected static ?string $title = 'Empleados';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->createAnother(false)
                ->form(
                    PersonAdvancedForm::getSchema(
                        type: PersonAdvancedForm::TYPE_EMPLOYEE,
                        additionalFields: [
                            TextInput::make('password')
                                ->label(function (Get $get) {
                                    if ($get('soft_deleted_exists')) {
                                        return 'Contraseña (Existente)';
                                    }

                                    return 'Contraseña (Nueva)';
                                })
                                ->password()
                                ->revealable()
                                ->placeholder(function (Get $get) {
                                    if ($get('soft_deleted_exists')) {
                                        return 'Se mantendrá la contraseña existente';
                                    }

                                    return 'Ingrese una contraseña para el empleado';
                                })
                                ->helperText(function (Get $get) {
                                    if ($get('soft_deleted_exists')) {
                                        return '🔄 Al reactivar este empleado, mantendrá su contraseña anterior.';
                                    }

                                    return '🔐 Ingrese una contraseña para el empleado.';
                                })
                                ->suffixIcon(function (Get $get) {
                                    return $get('soft_deleted_exists') ? 'heroicon-o-lock-closed' : '';
                                })
                                ->extraAttributes(function (Get $get) {
                                    return $get('soft_deleted_exists')
                                        ? ['style' => 'background-color: #fef3c7; border-color: #f59e0b;']
                                        : [];
                                })
                                ->required(fn (Get $get) => ! empty($get('email_search')) &&
                                    $get('relation_exists') === false &&
                                    ! $get('soft_deleted_exists')
                                ),
                        ]
                    )
                )
                ->mutateFormDataUsing(function (array $data): array {
                    return PersonAdvancedForm::validateFormData($data, PersonAdvancedForm::TYPE_EMPLOYEE);
                })
                ->using(function (array $data) {
                    try {
                        $personId = PersonAdvancedForm::getOrCreatePersonId($data, PersonAdvancedForm::TYPE_EMPLOYEE);

                        return Employee::create([
                            'person_id' => $personId,
                            'password'  => Hash::make($data['password']),
                        ]);
                    } catch (\Exception $e) {
                        // Si es una restauración, extraer el ID y retornar el registro restaurado
                        if (str_starts_with($e->getMessage(), 'RESTORED:')) {
                            $customerId = (int) str_replace('RESTORED:', '', $e->getMessage());

                            return Employee::find($customerId);
                        }
                        throw $e;
                    }
                }),
        ];
    }
}
