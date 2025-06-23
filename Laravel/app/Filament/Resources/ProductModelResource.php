<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductModelResource\Pages;
use App\Models\ProductModel;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
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
    
    /**
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre')
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->trashed() ? 'line-through text-gray-500 opacity-50' : ''
                    ]),
                Tables\Columns\TextColumn::make('product_brand.name')
                    ->label('Marca')
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->trashed() ? 'line-through text-gray-500 opacity-50' : ''
                    ]),
                Tables\Columns\TextColumn::make('products_count')
                    ->counts('products')
                    ->label('Cantidad de productos')
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->trashed() ? 'line-through text-gray-500 opacity-50' : ''
                    ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->hidden(fn (ProductModel $record) => $record->trashed())
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
                Tables\Actions\DeleteAction::make()
                    ->action(function (ProductModel $record) {
                        if ($record->products()->exists()) {
                            Notification::make()
                                ->title('No se puede eliminar')
                                ->body('Este modelo tiene productos asociados.')
                                ->danger()
                                ->send();
                            return;
                        }
                        
                        $record->delete();
                        
                        Notification::make()
                            ->title('Modelo eliminado')
                            ->body('El modelo ha sido eliminado correctamente.')
                            ->success()
                            ->send();
                    })->requiresConfirmation(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                //Tables\Actions\BulkActionGroup::make([
                //    Tables\Actions\DeleteBulkAction::make(),
                //]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
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
