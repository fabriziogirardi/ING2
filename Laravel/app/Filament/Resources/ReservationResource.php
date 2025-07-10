<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ReservationResource\Pages;
use App\Models\Reservation;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\HtmlString;

class ReservationResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $modelLabel = 'reserva';

    protected static ?string $pluralModelLabel = 'reservas';

    protected static ?string $navigationLabel = 'Reservas';

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

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
            ->recordUrl(null)
            ->recordClasses(fn (Reservation $record): string => ! $record->returned && $record->end_date->toDateString() < now()->toDateString()
                    ? 'bg-yellow-100'
                    : ''
            )
            ->columns([
                TextColumn::make('customer.person.full_name')
                    ->label('Cliente')
                    ->formatStateUsing(function ($state, $record) {
                        return new HtmlString(
                            e($state)." <span class='text-gray-500'><em>({$record->customer->person->email})</em></span>"
                        );
                    })
                    ->searchable(['first_name', 'last_name']),
                TextColumn::make('branch_product.product.name')
                    ->label('Maquinaria')
                    ->formatStateUsing(function ($state, $record) {
                        $product = $record->branch_product->product;

                        if ($product->trashed()) {
                            return new HtmlString(
                                '<span class="text-gray-500">'.e($state).' <em>(Eliminado)</em></span>'
                            );
                        }

                        return $state;
                    })
                    ->searchable(),
                TextColumn::make('branch_product.branch.name')
                    ->label('Sucursal')
                    ->formatStateUsing(function ($state, $record) {
                        $branch = $record->branch_product->branch;

                        if ($branch->trashed()) {
                            return new HtmlString(
                                '<span class="text-gray-500">'.e($state).' <em>(Cerrada)</em></span>'
                            );
                        }

                        return $state;
                    }),
                TextColumn::make('start_date')
                    ->dateTime('d/m/Y')
                    ->label('Fecha de Inicio'),
                TextColumn::make('end_date')
                    ->dateTime('d/m/Y')
                    ->label('Fecha de Fin'),
                IconColumn::make('retired_exists')
                    ->exists('retired')
                    ->tooltip(fn ($record) => $record->retired ? 'Retirada: '.$record->retired->created_at->format('d/m/Y H:i') : null)
                    ->boolean()
                    ->alignCenter()
                    ->label('Retirada'),
                IconColumn::make('returned_exists')
                    ->exists('returned')
                    ->tooltip(fn ($record) => $record->returned ? 'Devuelta: '.$record->returned->created_at->format('d/m/Y H:i') : null)
                    ->boolean()
                    ->alignCenter()
                    ->label('Devuelta'),
                IconColumn::make('cancelled')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->trashed())
                    ->alignCenter()
                    ->label('Cancelada'),
                ViewColumn::make('returned.rating')
                    ->label('Valoración')
                    ->view('filament.tables.columns.rating')
                    ->extraAttributes(fn ($record) => [
                        'class' => $record->trashed() ? 'hidden' : '',
                    ]),
            ])
            ->filters([
                Tables\Filters\Filter::make('date_range')
                    ->label('Rango de Fechas')
                    ->form([
                        DatePicker::make('start_date')
                            ->label('Fecha Inicio'),
                        DatePicker::make('end_date')
                            ->label('Fecha Fin'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when($data['start_date'], fn (Builder $query, $date) => $query->whereDate('start_date', '>=', $date)
                            )
                            ->when($data['end_date'], fn (Builder $query, $date) => $query->whereDate('end_date', '<=', $date)
                            );
                    }),

                Tables\Filters\SelectFilter::make('branch')
                    ->label('Sucursal')
                    ->relationship('branch', 'name')
                    ->preload()
                    ->multiple(),

                Tables\Filters\SelectFilter::make('status')
                    ->label('Estado')
                    ->options([
                        'pending'   => 'Pendiente',
                        'retired'   => 'Retirada',
                        'returned'  => 'Devuelta',
                        'cancelled' => 'Cancelada',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        if (empty($data['value'])) {
                            return $query;
                        }

                        return match ($data['value']) {
                            'pending' => $query->whereDoesntHave('retired')
                                ->whereDoesntHave('returned')
                                ->whereNull('deleted_at'),
                            'retired' => $query->whereHas('retired')
                                ->whereDoesntHave('returned')
                                ->whereNull('deleted_at'),
                            'returned' => $query->whereHas('returned')
                                ->whereNull('deleted_at'),
                            'cancelled' => $query->onlyTrashed(),
                            default     => $query,
                        };
                    }),

            ])
            ->actions([
                //
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
            'index' => Pages\ListReservations::route('/'),
            // 'create' => Pages\CreateReservation::route('/create'),
            // 'edit' => Pages\EditReservation::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->withTrashed();
    }
}
