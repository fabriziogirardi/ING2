<?php

namespace App\Livewire\Customer;

use App\Models\Reservation;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class ListReservations extends Component implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    public function table(Table $table): Table
    {
        return $table
            ->query(Reservation::query()
                ->withTrashed()
                ->where('customer_id', auth()->id())
                ->orderByDesc('id'))
            ->columns([
                TextColumn::make('code')
                    ->label('Código')
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->trashed() ? 'line-through text-gray-500 opacity-50' : '',
                    ]),
                TextColumn::make('branch_product.product.name')
                    ->label('Maquinaria')
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->trashed() ? 'line-through text-gray-500 opacity-50' : '',
                    ]),
                TextColumn::make('branch_product.branch.name')
                    ->label('Sucursal')
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->trashed() ? 'line-through text-gray-500 opacity-50' : '',
                    ]),
                TextColumn::make('start_date')
                    ->dateTime('d/m/Y')
                    ->label('Fecha de Inicio')
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->trashed() ? 'line-through text-gray-500 opacity-50' : '',
                    ]),
                TextColumn::make('end_date')
                    ->dateTime('d/m/Y')
                    ->label('Fecha de Fin')
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->trashed() ? 'line-through text-gray-500 opacity-50' : '',
                    ]),
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
            ->bulkActions([
                // ...
            ]);
    }

    public function render()
    {
        return view('livewire.customer.list-reservations');
    }
}
