<?php

namespace App\Filament\Resources;

use App\Filament\Forms\PersonAdvancedForm;
use App\Filament\Forms\PersonForm;
use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Coupon;
use App\Models\Customer;
use App\Models\GovernmentIdType;
use Exception;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationBadgeTooltip = 'Clientes activos';

    protected static ?string $modelLabel = 'cliente';

    protected static ?string $navigationLabel = 'Clientes';

    protected static ?string $navigationGroup = 'Cuentas';

    protected static ?string $navigationIcon = 'heroicon-o-user';

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
                Tables\Columns\ViewColumn::make('rating')
                    ->sortable(query: function (Builder $query, string $direction): Builder {
                        return $query->whereNull('deleted_at')
                            ->orderBy('rating', $direction);
                    })
                    ->label(function ($livewire) {
                        $sortColumn    = $livewire->getTableSortColumn();
                        $sortDirection = $livewire->getTableSortDirection();

                        if ($sortColumn === 'rating' && $sortDirection) {
                            return 'Rating ('.$sortDirection.')';
                        }

                        return 'Rating';
                    })
                    ->view('filament.tables.columns.rating')
                    ->state(fn ($record) => $record->trashed() ? null : $record->rating)
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->trashed() ? 'hidden' : '',
                    ]),
            ])
            ->filters([
                Tables\Filters\Filter::make('status')
                    ->form([
                        Select::make('status')
                            ->label('Estado del cliente')
                            ->options([
                                'active'     => 'Solo activos',
                                'blocked'    => 'Solo bloqueados',
                                'restorable' => 'Solo aptos para reanudar',
                            ])
                            ->default('active')
                            ->selectablePlaceholder(false),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $status = $data['status'] ?? 'active';

                        return match ($status) {
                            'blocked'    => $query->onlyTrashed(),
                            'active'     => $query->withoutTrashed(),
                            'restorable' => $query->onlyTrashed()->where('deleted_at', '<=', now()->subDays(90)),
                            default      => $query->withoutTrashed(),
                        };
                    })
                    ->indicateUsing(function (array $data): ?string {
                        $status = $data['status'] ?? 'active';

                        return match ($status) {
                            'blocked' => 'Mostrando: Solo bloqueados',
                            'active'  => 'Mostrando: Solo activos',
                            default   => null,
                        };
                    }),
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
                    ->modalHeading('Ver Cliente')
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
                    ->disabled(function ($record) {
                        if ($record->deleted_at) {
                            $daysSinceBlocked = $record->deleted_at->diffInDays(now());

                            return $daysSinceBlocked < 90;
                        }

                        return true;
                    })
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
                Tables\Actions\BulkAction::make('assign_coupon')
                    ->label('Asignar Cupón')
                    ->icon('heroicon-o-ticket')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Asignar Cupón a Clientes Seleccionados')
                    ->modalDescription('Selecciona el porcentaje de descuento que deseas asignar a los clientes seleccionados.')
                    ->modalSubmitActionLabel('Asignar Cupones')
                    ->form([
                        Select::make('discount_percentage')
                            ->label('Porcentaje de Descuento')
                            ->options([
                                10 => '10%',
                                25 => '25%',
                                50 => '50%',
                            ])
                            ->required()
                            ->placeholder('Selecciona el porcentaje de descuento')
                            ->native(false),
                    ])
                    ->action(function (array $data, Collection $records) {
                        $discountPercentage = $data['discount_percentage'];
                        $processedCount     = 0;

                        foreach ($records as $customer) {
                            Coupon::updateOrCreate(
                                ['customer_id' => $customer->id],
                                ['discount_percentage' => $discountPercentage]
                            );
                            $processedCount++;
                        }

                        Notification::make()
                            ->title('Cupones Asignados')
                            ->body("Se procesaron {$processedCount} cupones con {$discountPercentage}% de descuento.")
                            ->success()
                            ->send();
                    })
                    ->deselectRecordsAfterCompletion(),
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
            ])
            ->withTrashed();
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
        /** @var \Illuminate\Database\Eloquent\Builder $model */
        $model = static::getModel();
        return $model::count() > 0 ? (string) $model::count() : null;
    }
}
