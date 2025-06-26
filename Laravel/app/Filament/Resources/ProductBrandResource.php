<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductBrandResource\Pages;
use App\Models\ProductBrand;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductBrandResource extends Resource
{
    protected static ?string $model = ProductBrand::class;

    protected static ?string $modelLabel = 'marca de maquinaria';

    protected static ?string $navigationGroup = 'Maquinarias';

    protected static ?string $navigationLabel = 'Marcas';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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
                Tables\Columns\TextColumn::make('name')->searchable()
                    ->sortable()
                    ->label('Nombre de la marca')
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->trashed() ? 'line-through text-gray-500 opacity-50' : '',
                    ]),
                Tables\Columns\TextColumn::make('product_models_count')
                    ->counts('product_models')
                    ->label('Cantidad de modelos')
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->trashed() ? 'line-through text-gray-500 opacity-50' : '',
                    ]),
            ])
            ->filters([
                //
            ])
            ->actions([
                EditAction::make()
                    ->hidden(fn (ProductBrand $record) => $record->trashed())
                    ->form([
                        TextInput::make('name')
                            ->label('Nombre de la marca')
                            ->required()
                            ->unique(ProductBrand::class, 'name')
                            ->maxLength(255),
                    ]),
                Tables\Actions\DeleteAction::make()
                    ->action(function (ProductBrand $record) {
                        if ($record->product_models()->exists()) {
                            Notification::make()
                                ->title('No se puede eliminar')
                                ->body('Esta marca tiene modelos asociados.')
                                ->danger()
                                ->send();

                            return;
                        }

                        $record->delete();

                        Notification::make()
                            ->title('Marca eliminada')
                            ->body('La marca ha sido eliminada correctamente.')
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
            'index' => Pages\ListProductBrands::route('/'),
            //            'create' => Pages\CreateProductBrand::route('/create'),
            //            'edit'   => Pages\EditProductBrand::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        /** @var \Illuminate\Database\Eloquent\Builder $model */
        $model = static::getModel();

        return $model::count();
    }
}
