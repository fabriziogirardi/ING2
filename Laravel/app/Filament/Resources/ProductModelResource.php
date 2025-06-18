<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductModelResource\Pages;
use App\Models\ProductBrand;
use App\Models\ProductModel;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Unique;

class ProductModelResource extends Resource
{
    protected static ?string $model = ProductModel::class;

    protected static ?string $modelLabel = 'modelo de producto';

    protected static ?string $navigationGroup = 'Productos';

    protected static ?string $navigationLabel = 'Modelos';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('product_brand_id')
                    ->label('Marca')
                    ->relationship('brand', 'name')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->placeholder('Selecciona una marca'),
                TextInput::make('name')
                    ->label('Nombre del modelo')
                    ->required()
                    ->unique(modifyRuleUsing: function (Unique $rule, callable $get) {
                        return $rule
                            ->where('product_brand_id', $get('product_brand_id'))
                            ->where('name', $get('name'));
                    })
                    ->minLength(1)
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre'),
                Tables\Columns\TextColumn::make('product_brand.name')
                    ->label('Marca'),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()->default('with'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        Select::make('product_brand_id')
                            ->label('Marca')
                            ->relationship('brand', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->placeholder('Selecciona una marca'),
                        TextInput::make('name')
                            ->label('Nombre del modelo')
                            ->required()
                            ->unique(ignoreRecord: true, modifyRuleUsing: function (Unique $rule, callable $get) {
                                return $rule
                                    ->where('product_brand_id', $get('product_brand_id'))
                                    ->where('name', $get('name'));
                            })
                            ->minLength(1)
                            ->maxLength(255),
                    ]),
                Tables\Actions\DeleteAction::make()->requiresConfirmation(),
                Tables\Actions\RestoreAction::make()->requiresConfirmation()
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

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductModels::route('/'),
            // 'create' => Pages\CreateProductModel::route('/create'),
            // 'edit'   => Pages\EditProductModel::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
