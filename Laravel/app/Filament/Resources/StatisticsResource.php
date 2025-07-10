<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StatisticsResource\Pages;
use App\Models\Reservation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StatisticsResource extends Resource
{
    protected static ?string $model = Reservation::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-pie';

    protected static ?string $navigationGroup = 'Estadísticas';

    protected static ?string $navigationLabel = 'Ganancias';

    protected static ?string $pluralLabel = 'Ganancias';

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
                Tables\Columns\TextColumn::make('code')
                    ->label('Código de Reserva'),

                Tables\Columns\TextColumn::make('customer.person.full_name')
                    ->label('Cliente'),

                Tables\Columns\TextColumn::make('branch.name')
                    ->label('Sucursal'),

                Tables\Columns\TextColumn::make('total_amount')
                    ->label('Monto Total')
                    ->money('ARS'),

                Tables\Columns\TextColumn::make('total_refunds')
                    ->label('Monto devuelto')
                    ->money('ARS')
                    ->getStateUsing(fn (Reservation $record) => $record->refund?->amount ?? 0),

                Tables\Columns\TextColumn::make('net_revenue')
                    ->label('Ganancia Neta')
                    ->money('ARS')
                    ->getStateUsing(fn (Reservation $record) => max(0, $record->total_amount - ($record->refund?->amount ?? 0))),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Fecha Inicio')
                    ->date(),

                Tables\Columns\TextColumn::make('end_date')
                    ->label('Fecha Fin')
                    ->date(),
            ])
            ->filters([
                Tables\Filters\Filter::make('date_range')
                    ->label('Rango de Fechas')
                    ->form([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Fecha Inicio'),
                        Forms\Components\DatePicker::make('end_date')
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
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('30s')
            ->striped();
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
            'index' => Pages\ListStatistics::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->withTrashed();
    }

    public static function getChartData(?string $startDate = null, ?string $endDate = null, ?array $branchIds = null): array
    {
        $query = static::getModel()::query()
            ->withTrashed()
            ->with(['refund', 'branch_product.branch']);

        // Filtrar desde startDate si está presente
        if ($startDate) {
            $query->whereDate('start_date', '>=', $startDate);
        }

        // Filtrar hasta endDate si está presente
        if ($endDate) {
            $query->whereDate('end_date', '<=', $endDate);
        }

        // Filtrar sucursales si se proporcionan IDs
        if (! empty($branchIds)) {
            $query->whereHas('branch_product.branch', fn (Builder $q) => $q->whereIn('id', $branchIds)
            );
        }

        $reservations = $query->get();

        $totalRevenue = $reservations->sum('total_amount');
        $totalRefunds = $reservations->sum(fn ($reservation) => $reservation->refund?->amount ?? 0);

        return [
            'labels'   => ['Ventas', 'Devoluciones'],
            'datasets' => [[
                'data'            => [$totalRevenue, $totalRefunds],
                'backgroundColor' => ['#10B981', '#EF4444'],
                'borderColor'     => ['#059669', '#DC2626'],
                'borderWidth'     => 2,
            ]],
            'totals' => [
                'revenue'     => $totalRevenue,
                'refunds'     => $totalRefunds,
                'net_revenue' => $totalRevenue - $totalRefunds,
            ],
        ];
    }
}
