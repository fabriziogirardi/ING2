<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\BranchProduct;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\AttachAction;
use Filament\Tables\Table;

class BranchesRelationManager extends RelationManager
{
    protected static string $relationship = 'branches';

    // Método moderno para modificar la consulta base
    public function modifyRelationshipQuery(\Illuminate\Database\Eloquent\Builder $query, ?string $filter = null): \Illuminate\Database\Eloquent\Builder
    {
        // Incluir registros soft deleted de la tabla pivot
        return $query->withTrashed();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('quantity')
                    ->label('Cantidad')
                    ->numeric()
                    ->required()
                    ->maxLength(255)
                    ->default(0)
                    ->minValue(0)
                    ->maxValue(9999999),
            ]);
    }

    /**
     * @throws \Exception
     */
    public function table(Table $table): Table
    {
        return $table
            ->heading('Stock por sucursales')
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Sucursal'),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->numeric()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                AttachAction::make()
                    ->label('Agregar stock')  // Cambia el texto del botón
                    ->modalHeading('Agregar stock a sucursal')  // Opcional: cambia el título del modal
                    ->modalSubmitActionLabel('Agregar')  // Cambia el texto del botón de envío
                    ->attachAnother(false)
                    ->preloadRecordSelect()
                    ->form(fn (AttachAction $action): array => [
                        $action->getRecordSelect()->searchable(false),
                        TextInput::make('quantity')
                            ->required()
                            ->label('Cantidad'),
                    ])
                    ->action(function (array $data, $livewire) {
                        $productId = $livewire->getOwnerRecord()->id;
                        $branchId  = $data['recordId'];

                        // Verificar si existe un registro soft deleted
                        $existingPivot = BranchProduct::withTrashed()
                            ->where('product_id', $productId)
                            ->where('branch_id', $branchId)
                            ->first();

                        if ($existingPivot && $existingPivot->trashed()) {
                            // Restaurar el registro soft deleted y actualizar la cantidad
                            $existingPivot->restore();
                            $existingPivot->update(['quantity' => $data['quantity']]);

                            Notification::make()
                                ->title('Stock restaurado')
                                ->body('El stock de la sucursal ha sido restaurado y actualizado.')
                                ->success()
                                ->send();
                        } elseif ($existingPivot) {
                            // Si existe y no está eliminado, actualizar cantidad
                            $existingPivot->update(['quantity' => $data['quantity']]);

                            Notification::make()
                                ->title('Stock actualizado')
                                ->body('La cantidad de stock ha sido actualizada.')
                                ->success()
                                ->send();
                        } else {
                            // Crear nuevo registro
                            BranchProduct::create([
                                'product_id' => $productId,
                                'branch_id'  => $branchId,
                                'quantity'   => $data['quantity'],
                            ]);

                            Notification::make()
                                ->title('Stock agregado')
                                ->body('El stock ha sido agregado a la sucursal.')
                                ->success()
                                ->send();
                        }
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //    Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
