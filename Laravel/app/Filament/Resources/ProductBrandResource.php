<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductBrandResource\Pages;
use App\Models\ProductBrand;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class ProductBrandResource extends Resource
{
    protected static ?string $model = ProductBrand::class;

    protected static ?string $modelLabel = 'Marcas de productos';

    protected static ?string $navigationGroup = 'Productos';

    protected static ?string $navigationLabel = 'Marcas';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()
                    ->sortable()
                    ->label('Nombre de la marca'),
                Tables\Columns\TextColumn::make('product_models_count')
                    ->counts('product_models')
                    ->label('Cantidad de modelos'),
            ])
            ->filters([
                TrashedFilter::make()->default('with'),
            ])
            ->actions([
                EditAction::make()
                    ->form([
                        TextInput::make('name')
                            ->label('Nombre de la marca')
                            ->required()
                            ->unique(ProductBrand::class, 'name')
                            ->maxLength(255),
                    ]),
                Tables\Actions\DeleteAction::make()->requiresConfirmation(),
                Tables\Actions\RestoreAction::make()->requiresConfirmation()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make()
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
            'index' => Pages\ListProductBrands::route('/'),
            //            'create' => Pages\CreateProductBrand::route('/create'),
            //            'edit'   => Pages\EditProductBrand::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
