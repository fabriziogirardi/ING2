<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $modelLabel = 'categoría';

    protected static ?string $pluralModelLabel = 'categorías';

    protected static ?string $navigationGroup = 'Maquinarias';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('parent_id')
                    ->label('Categoría padre')
                    ->relationship('parent', 'name')
                    ->searchable()
                    ->preload()
                    ->placeholder('Selecciona una categoría padre o deja en blanco para raíz'),
                Forms\Components\TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->unique(modifyRuleUsing: function ($rule, callable $get) {
                        return $rule->where('parent_id', $get('parent_id'));
                    }),
                Forms\Components\TextInput::make('slug')
                    ->label('Slug')
                    ->disabled(true)
                    ->formatStateUsing(fn () => 'Este campo se genera automáticamente al guardar'),
                Forms\Components\TextInput::make('description')
                    ->label('Descripción')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                TextColumn::make('name')
                    ->label('Nombre'),
                TextColumn::make('parent')
                    ->label('Categoría padre')
                    ->getStateUsing(fn (Category $record) => $record->parent?->name ?? '-'),
                TextColumn::make('children_count')
                    ->label('Subcategorías')
                    ->counts('children'),
                TextColumn::make('products_count')
                    ->label('Maquinarias')
                    ->counts('products'),
                TextColumn::make('description')
                    ->label('Descripción')
                    ->limit(20)
                    ->placeholder('Sin descripción'),
                TextColumn::make('slug')
                    ->label('Slug')
                    ->limit(20)
                    ->hidden(),
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
                Tables\Actions\DeleteAction::make()
                    ->action(function (Category $record) {
                        if ($record->children()->count() > 0) {
                            Notification::make()
                                ->title('No se puede eliminar')
                                ->body('Esta categoría tiene subcategorías. Por favor, elimina las subcategorías antes de eliminar esta categoría.')
                                ->danger()
                                ->send();

                            return;
                        }

                        if ($record->products()->count() > 0) {
                            Notification::make()
                                ->title('No se puede eliminar')
                                ->body('Esta categoría tiene maquinarias asociadas. Por favor, elimina las maquinarias antes de eliminar esta categoría.')
                                ->danger()
                                ->send();

                            return;
                        }

                        $record->delete();

                        Notification::make()
                            ->title('Categoría eliminada')
                            ->body('La categoría ha sido eliminada correctamente.')
                            ->success()
                            ->send();
                    })->requiresConfirmation(),
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
        /** @var \Illuminate\Database\Eloquent\Builder $model */
        $model = static::getModel();

        return $model::count() > 0 ? (string) $model::count() : null;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])->withTrashed();
    }
}
