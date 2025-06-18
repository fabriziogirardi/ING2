<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Models\Employee;
use Carbon\Carbon;
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
        return $form
            ->schema([
                Select::make('person_id')
                    ->label('Email')
                    ->relationship(name: 'person', titleAttribute: 'email')
                    ->preload()
                    ->required()
                    ->placeholder('Ingrese un correo')
                    ->searchable()
                    ->createOptionForm([
                        TextInput::make('first_name')
                            ->label('Nombre')
                            ->required(),
                        TextInput::make('last_name')
                            ->label('Apellido')
                            ->required(),
                        TextInput::make('email')
                            ->label('Correo Electrónico')
                            ->required()
                            ->unique(ignoreRecord: Employee::class)
                            ->email(),
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
                            ->unique(modifyRuleUsing: function (Unique $rule, Get $get) {
                                return $rule->where('government_id_type_id', $get('government_id_type_id'));
                            })
                            ->required()
                            ->minLength(3)
                            ->maxLength(255)
                            ->string(),
                    ]),
                TextInput::make('password')
                    ->label('Contraseña')
                    ->password()
                    ->revealable()
                    ->placeholder(fn (?string $operation): string => $operation === 'create' ? 'Ingresa una contraseña' : 'Deja en blanco para no cambiar la contraseña')
                    ->default('')
                    ->maxLength(255)
                    ->minLength(3)
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->required(fn (string $operation): bool => $operation === 'create'),
            ]);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
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
                            ->schema([
                                TextInput::make('email')
                                    ->columnSpan(2)
                                    ->label('Correo Electrónico')
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
                    ->label('Bloquear cuenta')
                    ->icon('heroicon-o-lock-closed')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('¿Estás seguro de que querés bloquear esta cuenta?')
                    ->modalDescription('Esta acción impedirá el acceso del usuario hasta que se desbloquee.')
                    ->modalSubmitActionLabel('Sí, bloquear cuenta'),
                Tables\Actions\RestoreAction::make()
                    ->label('Reanudar cuenta')
                    ->icon('heroicon-o-lock-open')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('¿Querés reanudar el acceso a esta cuenta?')
                    ->modalDescription('El usuario podrá volver a iniciar sesión normalmente.')
                    ->modalSubmitActionLabel('Sí, reanudar cuenta'),
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
            ]);
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
            'index'  => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit'   => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
