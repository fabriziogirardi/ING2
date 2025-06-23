<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Illuminate\Validation\Rules\Unique;

class PersonForm {
    public static function getFormFields(): array
    {
        return [
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
                ->unique(ignoreRecord: true)
                ->minLength(3)
                ->maxLength(255)
                ->string(),
            DatePicker::make('birth_date')
                ->label('Fecha de Nacimiento')
                ->required()
                ->displayFormat('d/m/Y')
                ->maxDate(now()->subYears(18))
                ->date(),
            Select::make('government_id_type_id')
                ->label('Tipo de documento')
                ->relationship('government_id_type', 'name', fn ($query) => $query->orderBy('id'))
                ->required()
                ->default(1),
            TextInput::make('government_id_number')
                ->label('Número de documento')
                ->required()
                ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule, Get $get) {
                    return $rule->where('government_id_type_id', $get('government_id_type_id'));
                })
                ->minLength(3)
                ->maxLength(255)
                ->string(),
        ];
    }
}
