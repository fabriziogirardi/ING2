<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers\BranchesRelationManager;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductBrand;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $modelLabel = 'maquinaria';

    protected static ?string $pluralModelLabel = 'maquinarias';

    protected static ?string $navigationGroup = 'Maquinarias';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
                    ->afterStateUpdated(function (Set $set, Get $get) {
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
                    ->required()
                    ->panelLayout(
                        'grid'
                    )
                    ->reorderable()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre de la maquinaria'),
                TextColumn::make('product_model.product_brand.name')
                    ->label('Marca'),
                TextColumn::make('product_model.name')
                    ->label('Modelo'),
                TextColumn::make('price')
                    ->label('Precio')
                    ->money('ARS', 0, 'es'),
                TextColumn::make('min_days')
                    ->label('Días mínimos'),
                TextColumn::make('categories.fully_qualified_name')
                    ->label('Categorías')
                    ->badge(),
                ImageColumn::make('images_json')
                    ->label('Imágenes')
                    ->circular()
                    ->size(50)
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()->default('with'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
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
                Tables\Actions\DeleteAction::make()->requiresConfirmation(),
                Tables\Actions\RestoreAction::make()->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            BranchesRelationManager::class,
        ];
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
        return static::getModel()::count();
    }
}
