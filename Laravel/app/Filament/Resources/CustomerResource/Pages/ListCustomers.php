<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Forms\PersonAdvancedForm;
use App\Filament\Resources\CustomerResource;
use App\Models\Customer;
use Filament\Actions;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected static ?string $title = 'Clientes';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->createAnother(false)
                ->action(function (array $data) {
                    // Validar al momento de enviar
                    if (empty($data['email_search'])) {
                        Notification::make()
                            ->title('Error')
                            ->body('Debe ingresar un email antes de continuar.')
                            ->danger()
                            ->send();

                        return;
                    }

                    if ($data['relation_exists'] ?? false) {
                        Notification::make()
                            ->title('Error')
                            ->body('Esta persona ya es empleado.')
                            ->danger()
                            ->send();

                        return;
                    }
                })
                ->form(
                    PersonAdvancedForm::getSchema(
                        type: PersonAdvancedForm::TYPE_CUSTOMER,
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
                                ->readOnlyOn('create')
                                ->placeholder(function (Get $get) {
                                    if ($get('soft_deleted_exists')) {
                                        return 'Se mantendrá la contraseña existente';
                                    }

                                    return 'La contraseña se generará automáticamente';
                                })
                                ->helperText(function (Get $get) {
                                    if ($get('soft_deleted_exists')) {
                                        return '🔄 Al reactivar este cliente, mantendrá su contraseña anterior. Podrá cambiarla después desde su perfil.';
                                    }

                                    return '🔐 Se generará una contraseña segura automáticamente y se enviará por email al cliente.';
                                })
                                ->suffixIcon(function (Get $get) {
                                    if ($get('soft_deleted_exists')) {
                                        return 'heroicon-o-lock-closed'; // Icono de candado cerrado
                                    }

                                    return 'heroicon-o-key'; // Icono de llave
                                })
                                ->extraAttributes(function (Get $get) {
                                    if ($get('soft_deleted_exists')) {
                                        return [
                                            'style' => 'background-color: #fef3c7; border-color: #f59e0b;', // Fondo amarillo claro
                                        ];
                                    }

                                    return [
                                        'style' => 'background-color: #f0f9ff; border-color: #0ea5e9;', // Fondo azul claro
                                    ];
                                })
                                ->required(function (Get $get) {
                                    // Si existe un soft deleted, no es requerida (mantendrá la anterior)
                                    if ($get('soft_deleted_exists')) {
                                        return false;
                                    }

                                    // Si no hay email_search, es un cliente completamente nuevo
                                    if (empty($get('email_search'))) {
                                        return false; // No requerida para clientes nuevos
                                    }

                                    // Si hay email_search pero no existe relación, también es nuevo
                                    if ($get('relation_exists') === false) {
                                        return false; // No requerida para clientes nuevos
                                    }

                                    // En cualquier otro caso, podría ser requerida
                                    return true;
                                }),
                        ]
                    )
                )
                ->mutateFormDataUsing(function (array $data): array {
                    return PersonAdvancedForm::validateFormData($data, PersonAdvancedForm::TYPE_CUSTOMER);
                })
                ->using(function (array $data) {
                    try {
                        $personId = PersonAdvancedForm::getOrCreatePersonId($data, PersonAdvancedForm::TYPE_CUSTOMER);

                        return Customer::create([
                            'person_id' => $personId,
                        ]);
                    } catch (\Exception $e) {
                        // Si es una restauración, extraer el ID y retornar el registro restaurado
                        if (str_starts_with($e->getMessage(), 'RESTORED:')) {
                            $customerId = (int) str_replace('RESTORED:', '', $e->getMessage());

                            return Customer::find($customerId);
                        }
                        throw $e;
                    }
                }),
        ];
    }
}
