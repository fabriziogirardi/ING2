<?php

namespace App\Filament\Forms;

use App\Models\Customer;
use App\Models\Employee;
use App\Models\GovernmentIdType;
use App\Models\Person;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Validation\ValidationException;

class PersonAdvancedForm
{
    /**
     * Configuración del tipo de relación
     */
    const string TYPE_EMPLOYEE = 'employee';

    const string TYPE_CUSTOMER = 'customer';

    /**
     * Obtener los campos del formulario para buscar/crear persona y asociarla
     */
    public static function getSchema(string $type = self::TYPE_EMPLOYEE, array $additionalFields = []): array
    {
        $config = self::getTypeConfig($type);

        return [
            // Búsqueda de persona
            Section::make('Búsqueda de Persona')
                ->schema([
                    TextInput::make('email_search')
                        ->label('Email')
                        ->email()
                        ->placeholder('Ingrese el email para buscar o crear persona')
                        ->live(onBlur: true)
                        ->afterStateUpdated(function ($state, Set $set) use ($config) {
                            self::handlePersonSearch($state, $set, $config);
                        })
                        ->required(),
                ]),

            // Campos hidden para control de estado
            Hidden::make('person_id'),
            Hidden::make('person_found')->default(false),
            Hidden::make('relation_exists')->default(false),
            Hidden::make('soft_deleted_exists')->default(false),

            // Error: Relación ya existe
            Section::make('Error')
                ->schema([
                    Placeholder::make('relation_exists_info')
                        ->label('')
                        ->content(function (Get $get) use ($config) {
                            $personData = $get('person_data');
                            if (empty($personData)) {
                                return new HtmlString('');
                            }

                            return new HtmlString("
                                <div class='space-y-2 p-4 bg-red-50 rounded-lg border border-red-200'>
                                    <p class='text-red-800 font-medium'>⚠️ Esta persona ya es {$config['label_singular']}</p>
                                    <p><strong>Nombre:</strong> {$personData['first_name']} {$personData['last_name']}</p>
                                    <p><strong>Email:</strong> {$personData['email']}</p>
                                    <p class='text-red-600 text-sm mt-2'>No se puede crear {$config['label_article']} {$config['label_singular']} duplicado.</p>
                                </div>
                            ");
                        }),
                ])
                ->visible(fn (Get $get) => $get('relation_exists') === true),

            // Persona encontrada (válida)
            Section::make('Persona Encontrada')
                ->schema([
                    Placeholder::make('found_person_info')
                        ->label('')
                        ->content(function (Get $get) use ($config) {
                            $personData = $get('person_data');
                            if (empty($personData)) {
                                return new HtmlString('');
                            }

                            $bgColor   = $get('soft_deleted_exists') ? 'bg-orange-50 border-orange-200' : 'bg-green-50 border-green-200';
                            $textColor = $get('soft_deleted_exists') ? 'text-orange-800' : 'text-green-800';
                            $icon      = $get('soft_deleted_exists') ? '🔄' : '✓';
                            $message   = $get('soft_deleted_exists')
                                ? "Persona encontrada (se reactivará {$config['label_singular']} eliminado)"
                                : 'Persona encontrada';

                            return new HtmlString("
                                <div class='space-y-2 p-4 {$bgColor} rounded-lg border'>
                                    <p class='{$textColor} font-medium'>{$icon} {$message}</p>
                                    <p><strong>Nombre:</strong> {$personData['first_name']} {$personData['last_name']}</p>
                                    <p><strong>Email:</strong> {$personData['email']}</p>
                                    <p><strong>Documento:</strong> {$personData['government_id_number']}</p>
                                </div>
                            ");
                        }),
                ])
                ->visible(fn (Get $get) => $get('person_found') === true &&
                    $get('relation_exists') === false
                ),

            // Formulario para nueva persona
            Section::make('Datos de Nueva Persona')
                ->schema(self::getNewPersonFields())
                ->columns(2)
                ->visible(fn (Get $get) => $get('person_found') === false &&
                    $get('relation_exists') === false &&
                    ! empty($get('email_search'))
                ),

            // Campos adicionales específicos del tipo
            ...(empty($additionalFields) ? [] : [
                Section::make($config['additional_section_title'])
                    ->schema($additionalFields)
                    ->visible(fn (Get $get) => ! empty($get('email_search')) &&
                        $get('relation_exists') === false
                    ),
            ]),
        ];
    }

    /**
     * Manejar la búsqueda de persona
     */
    protected static function handlePersonSearch($state, Set $set, array $config): void
    {
        if (! $state) {
            $set('person_id', null);
            $set('person_found', false);
            $set('person_data', []);
            $set('relation_exists', false);
            $set('soft_deleted_exists', false);

            return;
        }

        $person = Person::where('email', $state)->first();

        if ($person) {
            // Verificar si ya existe la relación ACTIVA para esta persona (sin soft delete)
            $activeRelationExists = $config['model']::where('person_id', $person->id)->exists();

            // Verificar si existe una relación eliminada suavemente
            $softDeletedRelationExists = $config['model']::onlyTrashed()->where('person_id', $person->id)->exists();

            if ($activeRelationExists) {
                $set('person_id', null);
                $set('person_found', false);
                $set('relation_exists', true);
                $set('soft_deleted_exists', false);
                $set('person_data', [
                    'first_name' => $person->first_name,
                    'last_name'  => $person->last_name,
                    'email'      => $person->email,
                ]);

                return;
            }

            if ($softDeletedRelationExists) {
                $set('person_id', $person->id);
                $set('person_found', true);
                $set('relation_exists', false);
                $set('soft_deleted_exists', true);
                $set('person_data', [
                    'first_name'            => $person->first_name,
                    'last_name'             => $person->last_name,
                    'email'                 => $person->email,
                    'birth_date'            => $person->birth_date,
                    'government_id_type_id' => $person->government_id_type_id,
                    'government_id_number'  => $person->government_id_number,
                ]);

                return;
            }

            // Persona encontrada y no tiene la relación
            $set('person_id', $person->id);
            $set('person_found', true);
            $set('relation_exists', false);
            $set('soft_deleted_exists', false);
            $set('person_data', [
                'first_name'            => $person->first_name,
                'last_name'             => $person->last_name,
                'email'                 => $person->email,
                'birth_date'            => $person->birth_date,
                'government_id_type_id' => $person->government_id_type_id,
                'government_id_number'  => $person->government_id_number,
            ]);
        } else {
            // Persona no encontrada
            $set('person_id', null);
            $set('person_found', false);
            $set('relation_exists', false);
            $set('soft_deleted_exists', false);
            $set('person_data', ['email' => $state]);
        }
    }

    /**
     * Campos para nueva persona
     */
    protected static function getNewPersonFields(): array
    {
        return [
            TextInput::make('person_data.first_name')
                ->label('Nombre')
                ->required(fn (Get $get) => $get('person_found') === false &&
                    $get('relation_exists') === false &&
                    ! empty($get('email_search'))
                )
                ->minLength(3)
                ->maxLength(255),
            TextInput::make('person_data.last_name')
                ->label('Apellido')
                ->required(fn (Get $get) => $get('person_found') === false &&
                    $get('relation_exists') === false &&
                    ! empty($get('email_search'))
                )
                ->minLength(3)
                ->maxLength(255),
            TextInput::make('person_data.email')
                ->label('Email')
                ->email()
                ->required(fn (Get $get) => $get('person_found') === false &&
                    $get('relation_exists') === false &&
                    ! empty($get('email_search'))
                )
                ->default(fn (Get $get) => $get('email_search'))
                ->unique('people', 'email')
                ->maxLength(255),
            DatePicker::make('person_data.birth_date')
                ->label('Fecha de Nacimiento')
                ->required(fn (Get $get) => $get('person_found') === false &&
                    $get('relation_exists') === false &&
                    ! empty($get('email_search'))
                )
                ->displayFormat('d/m/Y')
                ->maxDate(now()->subYears(18)),
            Select::make('person_data.government_id_type_id')
                ->label('Tipo de documento')
                ->options(function () {
                    return GovernmentIdType::orderBy('id')->pluck('name', 'id');
                })
                ->required(fn (Get $get) => $get('person_found') === false &&
                    $get('relation_exists') === false &&
                    ! empty($get('email_search'))
                )
                ->default(1),
            TextInput::make('person_data.government_id_number')
                ->label('Número de documento')
                ->required(fn (Get $get) => $get('person_found') === false &&
                    $get('relation_exists') === false &&
                    ! empty($get('email_search'))
                )
                ->unique(
                    table: 'people',
                    column: 'government_id_number',
                    modifyRuleUsing: function (Unique $rule, Get $get) {
                        return $rule->where('government_id_type_id', $get('person_data.government_id_type_id'));
                    }
                )
                ->minLength(3)
                ->maxLength(255),
        ];
    }

    /**
     * Obtener configuración por tipo
     */
    protected static function getTypeConfig(string $type): array
    {
        $configs = [
            self::TYPE_EMPLOYEE => [
                'model'                    => Employee::class,
                'label_singular'           => 'empleado',
                'label_article'            => 'un',
                'additional_section_title' => 'Datos del Empleado',
            ],
            self::TYPE_CUSTOMER => [
                'model'                    => Customer::class,
                'label_singular'           => 'cliente',
                'label_article'            => 'un',
                'additional_section_title' => 'Datos del Cliente',
            ],
        ];

        return $configs[$type] ?? $configs[self::TYPE_EMPLOYEE];
    }

    /**
     * Validar datos antes de procesar
     */
    public static function validateFormData(array $data, string $type = self::TYPE_EMPLOYEE): array
    {
        $config = self::getTypeConfig($type);

        /** @var \Illuminate\Database\Eloquent\Builder $config['model'] */

        // Validación: No permitir si la relación ACTIVA ya existe
        if ($data['relation_exists'] ?? false) {
            throw ValidationException::withMessages([
                'email_search' => "No se puede crear {$config['label_article']} {$config['label_singular']} para una persona que ya lo es.",
            ]);
        }

        // Validar persona existente (solo relaciones activas)
        if (isset($data['person_id']) && $data['person_id']) {
            if ($config['model']::where('person_id', $data['person_id'])->exists()) {
                throw ValidationException::withMessages([
                    'email_search' => "Ya existe {$config['label_article']} {$config['label_singular']} activo para esta persona.",
                ]);
            }
        }

        // Validar nueva persona por email (solo relaciones activas)
        if (isset($data['person_data']['email'])) {
            $person = Person::where('email', $data['person_data']['email'])->first();
            if ($person && $config['model']::where('person_id', $person->id)->exists()) {
                throw ValidationException::withMessages([
                    'person_data.email' => "Ya existe {$config['label_article']} {$config['label_singular']} activo con este email.",
                ]);
            }
        }

        return $data;
    }

    /**
     * Crear o obtener persona y retornar su ID
     * También maneja la restauración de relaciones eliminadas suavemente
     *
     * @throws \Exception
     */
    public static function getOrCreatePersonId(array $data, string $type = self::TYPE_EMPLOYEE): int
    {
        $config = self::getTypeConfig($type);

        /** @var \Illuminate\Database\Eloquent\Builder $config['model'] */
        if ($data['person_found']) {
            $personId = $data['person_id'];

            // Si existe una relación eliminada suavemente, restaurarla
            if ($data['soft_deleted_exists'] ?? false) {
                $deletedRelation = $config['model']::onlyTrashed()->where('person_id', $personId)->first();
                if ($deletedRelation) {
                    $deletedRelation->restore();

                    // Actualizar los datos adicionales si los hay
                    $additionalData = array_filter(
                        $data,
                        function ($key) {
                            return ! in_array($key, ['person_id', 'person_found', 'relation_exists', 'soft_deleted_exists', 'person_data', 'email_search']);
                        },
                        ARRAY_FILTER_USE_KEY
                    );

                    if (! empty($additionalData)) {
                        $deletedRelation->update($additionalData);
                    }

                    // Retornar el ID de la relación restaurada para que no se cree una nueva
                    throw new \Exception("RESTORED:{$deletedRelation->id}");
                }
            }

            return $personId;
        } else {
            // Asegurar que el email esté incluido
            $personData = $data['person_data'];

            // Si el email no está en person_data, tomarlo de email_search
            if (empty($personData['email']) && ! empty($data['email_search'])) {
                $personData['email'] = $data['email_search'];
            }

            $person = Person::create($personData);

            return $person->id;
        }
    }
}
