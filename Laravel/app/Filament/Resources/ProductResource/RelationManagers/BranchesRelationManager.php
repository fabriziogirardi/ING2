<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\Branch;
use App\Models\BranchProduct;
use App\Models\Reservation;
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
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make()
                    ->action(function (Branch $record, array $data) {
                        if (Reservation::where('branch_product_id', $record->pivot_id)
                            ->where('start_date', ">=", now()->format('Y-m-d'))
                            ->whereDoesntHave('retired')
                            ->exists()
                        ) {
                            Notification::make()
                                ->title('No se puede eliminar')
                                ->body('No se puede eliminar el stock de esta sucursal porque tiene reservas pendientes.')
                                ->danger()
                                ->send();
                            
                            return;
                        }
                       
                        $record->delete();
                    }),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //    Tables\Actions\DeleteBulkAction::make(),
                // ]),
            ]);
    }
}
