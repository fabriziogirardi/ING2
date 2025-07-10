<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers\BranchesRelationManager;
use App\Models\Branch;
use App\Models\BranchProduct;
use App\Models\CancelPolicyProduct;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductBrand;
use App\Models\Reservation;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $modelLabel = 'maquinaria';

    protected static ?string $pluralModelLabel = 'maquinarias';

    protected static ?string $navigationGroup = 'Maquinarias';

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('product_model.product_brand_id')
                    ->columnSpan(2)
                    ->label('Marca')
                    ->hiddenOn('edit')
                    ->relationship('product_model.product_brand', 'name')
                    ->required()
                    ->dehydrated(false)
                    ->options(ProductBrand::pluck('name', 'id'))
                    ->afterStateUpdated(function (Set $set) {
                        $set('product_model_id', null);
                    })
                    ->placeholder('Selecciona una marca')
                    ->live(),
                Select::make('product_model_id')
                    ->columnSpan(2)
                    ->label('Modelo')
                    ->hiddenOn('edit')
                    ->relationship('product_model', 'name')
                    ->required()
                    ->disabled(fn (Get $get): bool => ! $get('product_model.product_brand_id'))
                    ->placeholder(fn (Get $get): string => $get('product_model.product_brand_id') ? 'Selecciona un modelo' : 'Selecciona una marca primero')
                    ->options(fn (Get $get): Collection => ProductBrand::find($get('product_model.product_brand_id'))?->product_models->pluck('name', 'id') ?? collect()),
                TextInput::make('name')
                    ->columnSpan(2)
                    ->label('Nombre de la maquinaria')
                    ->required()
                    ->unique(ignoreRecord: true, modifyRuleUsing: function ($rule, callable $get) {
                        return $rule->where('product_model_id', $get('product_model_id'));
                    }),
                TextInput::make('price')
                    ->label('Precio por día')
                    ->prefixIcon('heroicon-o-currency-dollar')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(999999.99)
                    ->default(0)
                    ->step(0.01),
                TextInput::make('min_days')
                    ->label('Días mínimos de alquiler')
                    ->numeric()
                    ->required()
                    ->minValue(1)
                    ->maxValue(365)
                    ->default(1),
                Select::make('categories')
                    ->columnSpan(2)
                    ->label('Categorías')
                    ->relationship('categories')
                    ->getOptionLabelFromRecordUsing(fn (Category $record) => $record->fully_qualified_name)
                    ->multiple()
                    ->preload()
                    ->required()
                    ->placeholder('Selecciona una o más categorías'),
                Select::make('cancel_policy')
                    ->label('Política de cancelación')
                    ->options([
                        'completa' => 'Completa',
                        'parcial'  => 'Parcial',
                        'nula'     => 'Nula',
                    ])
                    ->selectablePlaceholder(false)
                    ->afterStateHydrated(function (Select $component, ?string $state, $record) {
                        if ($state) {
                            return;
                        }
                        $value = match ($record?->cancelPolicy?->id) {
                            1       => 'completa',
                            2       => 'parcial',
                            default => 'nula',
                        };
                        $component->state($value);
                    })
                    ->required()
                    ->columnSpan(2)
                    ->helperText('Selecciona la política de cancelación para este producto.'),

                RichEditor::make('description')
                    ->columnSpan(2)
                    ->label('Descripción')
                    ->required()
                    ->toolbarButtons([
                        'bold',
                        'italic',
                        'underline',
                        'link',
                        'bulletList',
                        'numberList',
                        'blockquote',
                        'codeBlock',
                    ]),
                FileUpload::make('images_json')
                    ->columnSpan(2)
                    ->label('Imágenes')
                    ->multiple()
                    ->image()
                    ->panelLayout(
                        'grid'
                    )
                    ->reorderable()
                    ->required(),
            ]);
    }

    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->recordClasses(fn ($record) => $record->trashed() ? 'bg-gray-100' : '')
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre de la maquinaria')
                    ->searchable()
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->trashed() ? 'line-through text-gray-500 opacity-50' : '',
                    ]),
                TextColumn::make('product_model.product_brand.name')
                    ->label('Marca')
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->trashed() ? 'line-through text-gray-500 opacity-50' : '',
                    ]),
                TextColumn::make('product_model.name')
                    ->label('Modelo')
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->trashed() ? 'line-through text-gray-500 opacity-50' : '',
                    ]),
                TextColumn::make('price')
                    ->label('Precio')
                    ->money('ARS', 0, 'es')
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->trashed() ? 'line-through text-gray-500 opacity-50' : '',
                    ]),
                TextColumn::make('min_days')
                    ->label('Días mínimos')
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->trashed() ? 'line-through text-gray-500 opacity-50' : '',
                    ]),
                TextColumn::make('categories.fully_qualified_name')
                    ->label('Categorías')
                    ->limit(25)
                    ->badge()
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->trashed() ? 'line-through text-gray-500 opacity-50' : '',
                    ]),
                TextColumn::make('cancel_policy')
                    ->label('Política de cancelación')
                    ->state(function ($record) {
                        if ($record->cancelPolicy) {
                            return $record->cancelPolicy->id === 1 ? 'completa' : 'parcial';
                        }

                        return 'null';
                    })
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->trashed() ? 'line-through text-gray-500 opacity-50' : '',
                    ])
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'completa' => 'Completa',
                            'parcial'  => 'Parcial',
                            'null'     => 'Nula'
                        };
                    }),
                TextColumn::make('branches_with_stock')
                    ->label('Sucursales con Stock')
                    ->badge()
                    ->separator(',')
                    ->getStateUsing(function (Product $record) {
                        return BranchProduct::with('branch')
                            ->where('product_id', 2)
                            ->where('quantity', '>', 0)
                            ->get()
                            ->pluck('branch.name');
                    })
                    ->color('success')
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->trashed() ? 'line-through text-gray-500 opacity-50' : '',
                    ]),
                ImageColumn::make('images_json')
                    ->label('Imágenes')
                    ->circular()
                    ->size(50)
                    ->stacked()
                    ->limit()
                    ->limitedRemainingText(),
            ])
            ->filters([
                SelectFilter::make('branches_with_stock')
                    ->label('Sucursales con Stock')
                    ->options(fn () => Branch::pluck('name', 'id'))
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['value'])) {
                            return $query;
                        }

                        return $query->whereHas('branch_products', function ($subQuery) use ($data) {
                            $subQuery->where('branch_id', $data['value'])
                                ->where('quantity', '>', 0);
                        });
                    })
                    ->placeholder('Todas las sucursales'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hidden(fn ($record) => $record->trashed())
                    ->form([
                        TextInput::make('name')
                            ->columnSpan(2)
                            ->label('Nombre de la maquinaria')
                            ->required()
                            ->unique(ignoreRecord: true, modifyRuleUsing: function ($rule, callable $get) {
                                return $rule->where('product_model_id', $get('product_model_id'));
                            }),
                        TextInput::make('price')
                            ->label('Precio')
                            ->prefixIcon('heroicon-o-currency-dollar')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(999999.99)
                            ->step(0.01),
                        TextInput::make('min_days')
                            ->label('Días mínimos de alquiler')
                            ->numeric()
                            ->required()
                            ->minValue(0)
                            ->maxValue(365)
                            ->default(1),
                        Select::make('categories')
                            ->columnSpan(2)
                            ->label('Categorías')
                            ->relationship('categories')
                            ->getOptionLabelFromRecordUsing(fn (Category $record) => $record->fully_qualified_name)
                            ->multiple()
                            ->preload()
                            ->required()
                            ->placeholder('Selecciona una o más categorías'),
                        Select::make('cancel_policy')
                            ->label('Política de cancelación')
                            ->options([
                                'completa' => 'Completa',
                                'parcial'  => 'Parcial',
                                'nula'     => 'Nula',
                            ])
                            ->placeholder('Selecciona una política de cancelación')
                            ->required()
                            ->columnSpan(2)
                            ->helperText('Selecciona la política de cancelación para este producto.'),
                        RichEditor::make('description')
                            ->columnSpan(2)
                            ->label('Descripción')
                            ->required()
                            ->toolbarButtons([
                                'bold',
                                'italic',
                                'underline',
                                'link',
                                'bulletList',
                                'numberList',
                                'blockquote',
                                'codeBlock',
                            ]),
                        FileUpload::make('images_json')
                            ->columnSpan(2)
                            ->label('Imágenes')
                            ->multiple()
                            ->image()
                            ->panelLayout(
                                'grid'
                            )
                            ->reorderable(),
                    ]),
                Tables\Actions\DeleteAction::make()
                    ->action(function (Product $record) {
                        $hasPendingReservations = Reservation::whereRelation('branch_product', 'product_id', $record->id)
                            ->where(function (Builder $q) {
                                $q->where(function (Builder $subQ) {
                                    // Reservas futuras (no retiradas y end_date >= hoy)
                                    $subQ->whereDoesntHave('retired')
                                        ->where('end_date', '>=', now()->format('Y-m-d'));
                                })->orWhere(function (Builder $subQ) {
                                    // Retiradas pero no devueltas
                                    $subQ->whereHas('retired')->whereDoesntHave('returned');
                                });
                            })
                            ->exists();

                        if ($hasPendingReservations) {
                            Notification::make()
                                ->title('No se puede eliminar la maquinaria')
                                ->body('Existen reservas activas o retiradas y no devueltas que impiden la eliminación de esta maquinaria.')
                                ->danger()
                                ->send();

                            return;
                        }

                        $record->delete();
                    })
                    ->requiresConfirmation()
                    ->hidden(fn ($record) => $record->trashed()),
                Tables\Actions\RestoreAction::make(),
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
            BranchesRelationManager::class,
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
            'index'  => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit'   => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        /** @var Product $model */
        $model = static::getModel();

        return $model::count();
    }

    public static function afterSave($record, array $data): void
    {
        CancelPolicyProduct::where('product_id', $record->id)->delete();

        if (isset($data['cancel_policy']) && $data['cancel_policy'] !== 'nula') {
            $policyId = $data['cancel_policy'] === 'completa' ? 1 : 2;

            CancelPolicyProduct::create([
                'product_id'       => $record->id,
                'cancel_policy_id' => $policyId,
            ]);
        }
    }
}
