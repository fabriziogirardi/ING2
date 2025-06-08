<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use Exception;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationBadgeTooltip = 'Clientes activos';

    protected static ?string $modelLabel = 'cliente';

    protected static ?string $navigationLabel = 'Clientes';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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
                Tables\Columns\TextColumn::make('person.full_id_number')
                    ->label('Tipo y número de documento'),
                Tables\Columns\TextColumn::make('person.birth_date')
                    ->label('Fecha de Nacimiento')
                    ->date('d/m/Y'),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
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
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListCustomers::route('/'),
            // 'create' => Pages\CreateCustomer::route('/create'),
            // 'edit'   => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }
}
