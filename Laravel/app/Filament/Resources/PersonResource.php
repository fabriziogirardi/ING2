<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PersonResource\Pages;
use App\Filament\Resources\PersonResource\RelationManagers\CustomerRelationManager;
use App\Filament\Resources\PersonResource\RelationManagers\EmployeeRelationManager;
use App\Models\Person;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PersonResource extends Resource
{
    protected static ?string $model = Person::class;
    protected static ?string $modelLabel = 'persona';

    protected static ?string $pluralModelLabel = 'personas';

    protected static ?string $navigationLabel = 'Personas';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
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
                    ->minLength(3)
                    ->maxLength(255)
                    ->string(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                TextColumn::make('first_name')
                    ->label('Nombre'),
                TextColumn::make('last_name')
                    ->label('Apellido'),
                TextColumn::make('email')
                    ->label('Correo Electrónico')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('full_id_number')
                    ->label('Tipo y número de documento'),
                TextColumn::make('birth_date')
                    ->label('Fecha de Nacimiento')
                    ->date('d/m/Y'),
                IconColumn::make('customer_exists')
                    ->exists('customer')
                    ->boolean()
                    ->label('Cliente')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                IconColumn::make('employee_exists')
                    ->exists('employee')
                    ->boolean()
                    ->label('Empleado')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                IconColumn::make('manager_exists')
                    ->exists('manager')
                    ->boolean()
                    ->label('Gerente')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                //Tables\Actions\BulkActionGroup::make([
                //    Tables\Actions\DeleteBulkAction::make(),
                //]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // CustomerRelationManager::class,
            // EmployeeRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListPeople::route('/'),
            //'create' => Pages\CreatePerson::route('/create'),
            //'edit'   => Pages\EditPerson::route('/{record}/edit'),
        ];
    }
}
