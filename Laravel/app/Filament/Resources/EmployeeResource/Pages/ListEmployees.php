<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class ListEmployees extends ListRecords
{
    protected static string $resource = EmployeeResource::class;

    protected static ?string $title = 'Empleados';

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->form([
                    Fieldset::make('Datos personales')
                        ->relationship('person')
                        ->schema([
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
                            TextInput::make('email')
                                ->label('Correo Electrónico')
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
                        ->label('Contraseña')
                        ->password()
                        ->revealable()
                        ->placeholder('Para mantener la contraseña actual, dejar este campo en blanco.')
                        ->default('')
                        ->maxLength(255)
                        ->minLength(3)
                        ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->required(fn (string $operation): bool => $operation === 'create'),
                ]),
        ];
    }

    protected function makeTable(): Table
    {
        return parent::makeTable()->recordUrl(null)->recordAction(null);
    }
}
