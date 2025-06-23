<?php

namespace App\Filament\Resources;

use App\Filament\Forms\PersonAdvancedForm;
use App\Filament\Forms\PersonForm;
use App\Filament\Resources\EmployeeResource\Pages;
use App\Models\Employee;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Unique;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;

    protected static ?string $modelLabel = 'empleado';

    protected static ?string $navigationLabel = 'Empleados';

    protected static ?string $navigationGroup = 'Cuentas';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema(
            PersonAdvancedForm::getSchema(
                type: PersonAdvancedForm::TYPE_EMPLOYEE,
                additionalFields: [
                    TextInput::make('password')
                        ->label('Contraseña')
                        ->password()
                        ->revealable()
                        ->placeholder('Ingresa una contraseña')
                        ->maxLength(255)
                        ->minLength(3)
                        ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->required(fn (Get $get) => ! empty($get('email_search')) &&
                            $get('relation_exists') === false
                        ),
                ]
            )
        );
    }

    protected static function getNewPersonFields(): array
    {
        return [
            TextInput::make('person_data.first_name')
                ->label('Nombre')
                ->required()
                ->minLength(3)
                ->maxLength(255),
            TextInput::make('person_data.last_name')
                ->label('Apellido')
                ->required()
                ->minLength(3)
                ->maxLength(255),
            DatePicker::make('person_data.birth_date')
                ->label('Fecha de Nacimiento')
                ->required()
                ->displayFormat('d/m/Y')
                ->maxDate(now()->subYears(18)),
            Select::make('person_data.government_id_type_id')
                ->label('Tipo de documento')
                ->relationship('governmentIdType', 'name', fn ($query) => $query->orderBy('id'))
                ->required()
                ->default(1),
            TextInput::make('person_data.government_id_number')
                ->label('Número de documento')
                ->required()
                ->unique(
                    table: 'people',
                    modifyRuleUsing: function (Unique $rule, Get $get) {
                        return $rule->where('government_id_type_id', $get('person_data.government_id_type_id'));
                    }
                )
                ->minLength(3)
                ->maxLength(255),
        ];
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->searchPlaceholder('Buscar por correo')
            ->columns([
                Tables\Columns\TextColumn::make('person.first_name')
                    ->label('Nombre'),
                Tables\Columns\TextColumn::make('person.last_name')
                    ->label('Apellido'),
                Tables\Columns\TextColumn::make('person.email')
                    ->label('Correo Electrónico')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('person.full_id_number')
                    ->label('Tipo y número de documento'),
                Tables\Columns\TextColumn::make('person.birth_date')
                    ->label('Fecha de Nacimiento')
                    ->date('d/m/Y'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        Fieldset::make('Datos personales')
                            ->relationship('person')
                            ->schema(PersonForm::getFormFields()),
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
                Tables\Actions\DeleteAction::make()
                    ->label('Deshabilitar'),
                Tables\Actions\RestoreAction::make()
                    ->label('Habilitar'),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //    Tables\Actions\DeleteBulkAction::make(),
                //    Tables\Actions\RestoreBulkAction::make(),
                // ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])->withTrashed();
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            // 'create' => Pages\CreateEmployee::route('/create'),
            // 'edit'   => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
