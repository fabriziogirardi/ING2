<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $modelLabel = 'categoría';

    protected static ?string $pluralModelLabel = 'categorías';

    protected static ?string $navigationGroup = 'Maquinarias';

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
                TextColumn::make('name')
                    ->label('Nombre'),
                TextColumn::make('parent')
                    ->label('Categoría padre')
                    ->getStateUsing(fn (Category $record) => $record->parent?->name ?? '-'),
                TextColumn::make('children_count')
                    ->label('Subcategorías')
                    ->counts('children'),
                TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(50),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->limit(50),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->form([
                        Forms\Components\Select::make('parent_id')
                            ->label('Categoría padre')
                            ->relationship('parent', 'name')
                            ->searchable()
                            ->preload()
                            ->placeholder('Selecciona una categoría padre o deja en blanco para raíz'),
                        Forms\Components\TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->unique(ignoreRecord: true, modifyRuleUsing: function ($rule, callable $get) {
                                return $rule->where('parent_id', $get('parent_id'));
                            }),
                        Forms\Components\TextInput::make('slug')
                            ->label('Slug')
                            ->disabled(true)
                            ->formatStateUsing(fn () => 'Este campo se genera automáticamente al guardar'),
                        Forms\Components\TextInput::make('description')
                            ->label('Descripción')
                            ->maxLength(255),
                    ]),
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
            'index' => Pages\ListCategories::route('/'),
            // 'create' => Pages\CreateCategory::route('/create'),
            // 'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
