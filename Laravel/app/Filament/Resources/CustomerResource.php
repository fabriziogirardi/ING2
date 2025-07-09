<?php

namespace App\Filament\Resources;

use App\Filament\Forms\PersonAdvancedForm;
use App\Filament\Forms\PersonForm;
use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use Exception;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
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

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationBadgeTooltip = 'Clientes activos';

    protected static ?string $modelLabel = 'cliente';

    protected static ?string $navigationLabel = 'Clientes';

    protected static ?string $navigationGroup = 'Cuentas';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema(
            PersonAdvancedForm::getSchema(
                type: PersonAdvancedForm::TYPE_CUSTOMER,
                additionalFields: [
                    TextInput::make('customer_code')
                        ->label('Código de Cliente')
                        ->placeholder('Código único del cliente')
                        ->maxLength(50)
                        ->unique(Customer::class, 'customer_code')
                        ->required(fn (Get $get) => ! empty($get('email_search')) &&
                            $get('relation_exists') === false
                        ),
                    Textarea::make('notes')
                        ->label('Notas')
                        ->placeholder('Notas adicionales sobre el cliente')
                        ->maxLength(1000),
                ]
            )
        );
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
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
                Tables\Columns\ViewColumn::make('rating')
                    ->view('filament.tables.columns.rating')
                    ->state(fn ($record) => $record->trashed() ? null : $record->rating)
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->trashed() ? 'hidden' : '',
                    ]),
            ])
            ->filters([
                Tables\Filters\Filter::make('only_trashed')
                    ->label('Sólo clientes bloqueados')
                    ->query(fn (Builder $query): Builder => $query->onlyTrashed()),
            ])
            ->actions([
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
                            ->dehydrated(fn (?string $state): bool => filled($state)),
                    ]),
                //                Tables\Actions\DeleteAction::make()
                //                    ->label('Bloquear cuenta')
                //                    ->icon('heroicon-o-lock-closed')
                //                    ->color('danger')
                //                    ->requiresConfirmation()
                //                    ->modalHeading('¿Estás seguro de que querés bloquear esta cuenta?')
                //                    ->modalDescription('Esta acción impedirá el acceso del usuario hasta que se desbloquee.')
                //                    ->modalSubmitActionLabel('Sí, bloquear cuenta')
                //                    ->successNotification(
                //                        Notification::make()
                //                            ->title('Cuenta bloqueada')
                //                            ->body('El usuario ya no podrá iniciar sesión.')
                //                            ->success(),
                //                    ),
                Tables\Actions\RestoreAction::make()
                    ->label('Reanudar cuenta')
                    ->icon('heroicon-o-lock-open')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('¿Querés reanudar el acceso a esta cuenta?')
                    ->modalDescription('El usuario podrá volver a iniciar sesión normalmente.')
                    ->modalSubmitActionLabel('Sí, reanudar cuenta')
                    ->after(function ($record) {
                        $record->rating             = 3.00;
                        $record->reservations_count = 0;
                        $record->save();
                    })
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
                // ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])->withTrashed();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            // 'create' => Pages\CreateCustomer::route('/create'),
            // 'edit'   => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
