<?php

namespace App\Filament\Resources;

use App\Filament\Forms\PersonAdvancedForm;
use App\Filament\Forms\PersonForm;
use App\Filament\Resources\EmployeeResource\Pages;
use App\Models\Employee;
use App\Models\GovernmentIdType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
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

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

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
            ->recordAction('view')
            ->searchPlaceholder('Buscar por correo')
            ->recordClasses(fn ($record) => $record->trashed() ? 'bg-gray-100' : '')
            ->columns([
                Tables\Columns\TextColumn::make('person.first_name')
                    ->label('Nombre')
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->trashed() ? 'line-through text-gray-500 opacity-50' : '',
                    ]),
                Tables\Columns\TextColumn::make('person.last_name')
                    ->label('Apellido')
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->trashed() ? 'line-through text-gray-500 opacity-50' : '',
                    ]),
                Tables\Columns\TextColumn::make('person.email')
                    ->label('Correo Electrónico')
                    ->searchable()
                    ->sortable()
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->trashed() ? 'line-through text-gray-500 opacity-50' : '',
                    ]),
                Tables\Columns\TextColumn::make('person.full_id_number')
                    ->label('Tipo y número de documento')
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->trashed() ? 'line-through text-gray-500 opacity-50' : '',
                    ]),
                Tables\Columns\TextColumn::make('person.birth_date')
                    ->label('Fecha de Nacimiento')
                    ->date('d/m/Y')
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->trashed() ? 'line-through text-gray-500 opacity-50' : '',
                    ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make('view')
                    ->label(false)
                    ->icon(false)
                    ->color('gray')
                    ->form([
                        Fieldset::make('Datos personales')
                            ->relationship('person')
                            ->schema([
                                Placeholder::make('first_name')
                                    ->label('Nombre')
                                    ->content(fn (Get $get) => $get('first_name') ?? 'No especificado'),
                                Placeholder::make('last_name')
                                    ->label('Apellido')
                                    ->content(fn (Get $get) => $get('last_name') ?? 'No especificado'),
                                Placeholder::make('email')
                                    ->label('Correo Electrónico')
                                    ->content(fn (Get $get) => $get('email') ?? 'No especificado'),
                                Placeholder::make('birth_date')
                                    ->label('Fecha de Nacimiento')
                                    ->content(fn (Get $get) => $get('birth_date')
                                        ? \Carbon\Carbon::parse($get('birth_date'))->format('d/m/Y')
                                        : 'No especificada'),
                                Placeholder::make('government_id_type_name')
                                    ->label('Tipo de documento')
                                    ->content(fn (Get $get) => GovernmentIdType::find($get('government_id_type_id'))?->name ?? 'No especificado'),
                                Placeholder::make('government_id_number')
                                    ->label('Número de documento')
                                    ->content(fn (Get $get) => $get('government_id_number') ?? 'No especificado'),
                            ]),
                    ])
                    ->modalHeading('Ver Empleado')
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Cerrar'),
                Tables\Actions\EditAction::make()
                    ->hidden(fn ($record) => $record->trashed())
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
                    ->label('Bloquear cuenta')
                    ->icon('heroicon-o-lock-closed')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('¿Estás seguro de que querés bloquear esta cuenta?')
                    ->modalDescription('Esta acción impedirá el acceso del usuario hasta que se desbloquee.')
                    ->modalSubmitActionLabel('Sí, bloquear cuenta')
                    ->successNotification(
                        Notification::make()
                            ->title('Cuenta bloqueada')
                            ->body('El usuario ya no podrá iniciar sesión.')
                            ->success(),
                    ),
                Tables\Actions\RestoreAction::make()
                    ->label('Reanudar cuenta')
                    ->icon('heroicon-o-lock-open')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('¿Querés reanudar el acceso a esta cuenta?')
                    ->modalDescription('El usuario podrá volver a iniciar sesión normalmente.')
                    ->modalSubmitActionLabel('Sí, reanudar cuenta')
                    ->successNotification(
                        Notification::make()
                            ->title('Cuenta reanudada')
                            ->body('El usuario ahora puede iniciar sesión nuevamente.')
                            ->success(),
                    ),
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
        /** @var \Illuminate\Database\Eloquent\Builder $model */
        $model = static::getModel();
        return $model::count() > 0 ? (string) $model::count() : null;
    }
}
