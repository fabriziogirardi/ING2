<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationResource\Pages;
use App\Models\Reservation;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

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
                TextColumn::make('customer.person.full_name')
                    ->label('Cliente'),
                TextColumn::make('branch_product.product.name')
                    ->label('Maquinaria'),
                TextColumn::make('branch_product.branch.name')
                    ->label('Sucursal'),
                TextColumn::make('start_date')
                    ->dateTime('d/m/Y')
                    ->label('Fecha de Inicio'),
                TextColumn::make('end_date')
                    ->dateTime('d/m/Y')
                    ->label('Fecha de Fin'),
                IconColumn::make('retired_exists')
                    ->exists('retired')
                    ->boolean()
                    ->alignCenter()
                    ->label('Retirada'),
                IconColumn::make('returned_exists')
                    ->exists('returned')
                    ->boolean()
                    ->alignCenter()
                    ->label('Devuelta'),
            ])
            ->filters([
                //
            ])
            ->actions([
                DeleteAction::make()
                    ->label(fn ($record) => $record->retired_exists || $record->returned_exists || $record->start_date > now() ? 'No se puede cancelar' : 'Cancelar reserva')
                    ->disabled(fn ($record) => $record->retired_exists || $record->returned_exists || $record->start_date > now()),
                Action::make('restore')
                    ->label('Cancelada')
                    ->link()
                    ->hidden(fn ($record) => ! $record->trashed())
                    ->color('danger')
                    ->disabled(),
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
            'index' => Pages\ListReservations::route('/'),
            // 'create' => Pages\CreateReservation::route('/create'),
            // 'edit' => Pages\EditReservation::route('/{record}/edit'),
        ];
    }
}
