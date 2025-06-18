<?php

namespace App\Filament\Resources\CustomerResource\Pages;

use App\Filament\Resources\CustomerResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class ListCustomers extends ListRecords
{
    protected static string $resource = CustomerResource::class;

    protected static ?string $title = 'Clientes';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->form([
                    Fieldset::make('Datos personales')
                        ->relationship('person')
                        ->schema([
                            TextInput::make('email')
                                ->label('Correo Electrónico')
                                ->columnSpan(2)
                                ->required()
                                ->minLength(3)
                                ->maxLength(255)
                                ->string(),
                            TextInput::make('first_name')
                                ->label('Nombre')
                                ->required()
                                ->minLength(3)
                                ->maxLength(255)
                                ->string(),
                            TextInput::make('last_name')
                                ->label('Apellido')
                                ->required()
                                ->minLength(3)
                                ->maxLength(255)
                                ->string(),
                            DatePicker::make('birth_date')
                                ->label('Fecha de Nacimiento')
                                ->required()
                                ->displayFormat('d/m/Y')
                                ->date()
                                ->maxDate(Carbon::now()->subYears(18)),
                            Select::make('government_id_type_id')
                                ->label('Tipo de documento')
                                ->relationship('government_id_type', 'name', fn ($query) => $query->orderBy('id'))
                                ->required()
                                ->default(1),
                            TextInput::make('government_id_number')
                                ->label('Número de documento')
                                ->required()
                                ->minLength(3)
                                ->maxLength(255)
                                ->string(),
                        ]),
                    TextInput::make('password')
                        ->columnSpan(2)
                        ->label('Contraseña')
                        ->password()
                        ->revealable()
                        ->hiddenOn('create')
                        ->placeholder(fn (string $operation): string => $operation === 'create' ? 'Ingresa una contraseña' : 'Deja en blanco para no cambiar la contraseña')
                        ->default('')
                        ->maxLength(255)
                        ->minLength(3)
                        ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                        ->dehydrated(fn (?string $state): bool => filled($state)),
                ]
                )
                ->using(function (array $data, string $model): Model {
                    return $model::create($data);
                }),
        ];
    }
}
