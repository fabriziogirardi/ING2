<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use Carbon\Carbon;
use Exception;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Unique;

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
        return $form
            ->schema([
                Select::make('person_id')
                    ->label('Email')
                    ->relationship(
                        name: 'person',
                        titleAttribute: 'email',
                        modifyQueryUsing: fn (Builder $query) => $query->doesntHave('customer')
                    )
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
                            ->label('Número de documento ')
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
                    ->disabled(fn (string $operation): bool => $operation === 'create')
                    ->placeholder(fn (string $operation): string => $operation === 'create'
                        ? 'La contraseña se generará automáticamente y se enviará por correo electrónico'
                        : 'Deja en blanco para no cambiar la contraseña'
                    )
                    ->default('')
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                    ->dehydrated(fn (?string $state): bool => filled($state)),
            ]);
    }

    /**
     * @throws Exception
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
                Tables\Columns\ViewColumn::make('rating')
                    ->view('filament.tables.columns.rating'),
            ])
            ->filters([
                //
            ])
            ->actions([
                //                Tables\Actions\EditAction::make()
                //                    ->disabled()
                //                    ->extraAttributes(['class' => 'cursor-not-allowed pointer-events-auto hover:no-underline']),

                Tables\Actions\DeleteAction::make()->requiresConfirmation(),
                // Tables\Actions\RestoreAction::make()->requiresConfirmation(),
            ])
            ->bulkActions([
                //                Tables\Actions\BulkActionGroup::make([
                //                    Tables\Actions\DeleteBulkAction::make(),
                //                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
        ];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            // 'edit'   => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
