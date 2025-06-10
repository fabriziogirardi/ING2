<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use App\Models\Category;
use App\Models\ProductBrand;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Collection;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->createAnother(false)
                ->form([
                    Select::make('model.product_brand_id')
                        ->columnSpan(2)
                        ->label('Marca')
                        ->relationship('model.brand', 'name')
                        ->required()
                        ->dehydrated(false)
                        ->options(ProductBrand::pluck('name', 'id'))
                        ->afterStateUpdated(function (Get $get, callable $set) {
                            $set('product_model_id', null);
                        })
                        ->placeholder('Selecciona una marca')
                        ->live(),
                    Select::make('product_model_id')
                        ->columnSpan(2)
                        ->label('Modelo')
                        ->relationship('model', 'name')
                        ->required()
                        ->placeholder(fn (Get $get): string => $get('model.product_brand_id') ? 'Selecciona un modelo' : 'Selecciona una marca primero')
                        ->options(fn (Get $get): Collection => ProductBrand::find($get('model.product_brand_id'))?->models->pluck('name', 'id') ?? collect()),
                    TextInput::make('name')
                        ->columnSpan(2)
                        ->label('Nombre del producto')
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
                        ->reorderable()
                        ->appendFiles(),
                    // ->saveRelationshipsUsing(function ($component, $state, $record) {
                    //    foreach ($state as $filePath) {
                    //        $record->images()->create([
                    //            'path' => $filePath,
                    //        ]);
                    //    }
                    // }),
                ]),
        ];
    }
}
