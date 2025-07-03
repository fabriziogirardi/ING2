<?php

// Laravel/app/Filament/Resources/CancelPolicyProductResource.php
namespace App\Filament\Resources;

use App\Models\CancelPolicyProduct;
use App\Models\Product;
use App\Models\CancelPolicy;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CancelPolicyProductResource extends Resource
{
    protected static ?string $model = CancelPolicyProduct::class;
    protected static ?string $navigationIcon = 'heroicon-o-link';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('product_id')
                    ->label('Product')
                    ->options(Product::all()->pluck('name', 'id'))
                    ->required(),
                Forms\Components\Select::make('cancel_policy_id')
                    ->label('Cancel Policy')
                    ->options(CancelPolicy::all()->pluck('name', 'id'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('product.name')->label('Product'),
                Tables\Columns\TextColumn::make('cancel_policy.name')->label('Cancel Policy'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCancelPolicyProducts::route('/'),
            'create' => Pages\CreateCancelPolicyProduct::route('/create'),
            'edit' => Pages\EditCancelPolicyProduct::route('/{record}/edit'),
        ];
    }
}
