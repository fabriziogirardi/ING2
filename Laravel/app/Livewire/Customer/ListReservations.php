<?php

namespace App\Livewire\Customer;

use App\Models\Reservation;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Component;

class ListReservations extends Component implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(Reservation::query()->withoutGlobalScope(SoftDeletingScope::class)->where('customer_id', auth()->id())->orderByDesc('id'))
            ->columns([
                TextColumn::make('code')
                    ->label('Código'),
                TextColumn::make('branch_product.product.name')
                    ->label('Producto'),
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
                    ->label('Retirada')
                    ->alignCenter()
                    ->boolean(),
                IconColumn::make('returned_exists')
                    ->exists('returned')
                    ->label('Devuelta')
                    ->alignCenter()
                    ->boolean(),
            ])
            ->filters([
                // ...
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
                // ...
            ]);
    }

    public function render()
    {
        return view('livewire.customer.list-reservations');
    }
}
