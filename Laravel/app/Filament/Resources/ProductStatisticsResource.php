<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductStatisticsResource\Pages;
use App\Models\Product;
use App\Models\Branch;
use Carbon\Carbon;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ProductStatisticsResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-chart-bar';
    protected static ?string $navigationGroup = 'Estadísticas';
    protected static ?string $navigationLabel = 'Top Maquinarias Más Vendidas';

    protected static ?string $modelLabel = 'Top Maquinarias Más Vendidas';
    protected static ?string $pluralModelLabel = 'Top Maquinarias Más Vendidas';

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(static::getBaseQuery())
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Imagen')
                    ->getStateUsing(function (Product $record) {
                        $images = $record->images_json ?? [];
                        return !empty($images) ? $images[0] : null;
                    })
                    ->defaultImageUrl('/images/no-image.png')
                    ->height(60)
                    ->width(60)
                    ->circular(),

                Tables\Columns\TextColumn::make('name')
                    ->label('Nombre del Producto')
                    ->wrap(),

                Tables\Columns\TextColumn::make('reservations_count')
                    ->label('Cantidad Vendida')
                    ->alignCenter()
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'gray'),

                Tables\Columns\TextColumn::make('product_model.brand.name')
                    ->label('Marca'),

                Tables\Columns\TextColumn::make('product_model.name')
                    ->label('Modelo'),

                Tables\Columns\TextColumn::make('price')
                    ->label('Precio')
                    ->money('ARS'),
            ])
            ->filters([
                Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Fecha Inicio')
                            ->placeholder('Seleccionar fecha inicio'),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Fecha Fin')
                            ->placeholder('Seleccionar fecha fin'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        $startDate = $data['start_date'] ?? null;
                        $endDate = $data['end_date'] ?? null;

                        // Obtener el filtro de sucursal desde la URL
                        $branchId = request()->get('tableFilters.branch_id.value');

                        // Aplicar withCount con los filtros correspondientes
                        $query->withCount([
                            'reservations as reservations_count' => function ($reservationQuery) use ($startDate, $endDate, $branchId) {
                                if ($startDate) {
                                    $reservationQuery->where('start_date', '>=', $startDate);
                                }
                                if ($endDate) {
                                    $reservationQuery->where('end_date', '<=', $endDate);
                                }
                                if ($branchId) {
                                    $reservationQuery->whereHas('branch_product', function ($q) use ($branchId) {
                                        $q->where('branch_id', $branchId);
                                    });
                                }
                            }
                        ]);

                        // Filtrar productos que pertenecen a la sucursal (si está seleccionada)
                        if ($branchId) {
                            $query->whereHas('branches', function ($q) use ($branchId) {
                                $q->where('branches.id', $branchId);
                            });
                        }

                        return $query->orderBy('reservations_count', 'desc');
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['start_date'] ?? null) {
                            $indicators[] = 'Desde: ' . Carbon::parse($data['start_date'])->format('d/m/Y');
                        }

                        if ($data['end_date'] ?? null) {
                            $indicators[] = 'Hasta: ' . Carbon::parse($data['end_date'])->format('d/m/Y');
                        }

                        return $indicators;
                    }),

                SelectFilter::make('branch_id')
                    ->label('Sucursal')
                    ->options(Branch::pluck('name', 'id')->toArray())
                    ->preload()
                    ->query(function (Builder $query, array $data): Builder {
                        $branchId = $data['value'] ?? null;

                        // Obtener los filtros de fecha desde la URL
                        $startDate = request()->get('tableFilters.date_range.start_date');
                        $endDate = request()->get('tableFilters.date_range.end_date');

                        // Aplicar withCount con los filtros correspondientes
                        $query->withCount([
                            'reservations as reservations_count' => function ($reservationQuery) use ($startDate, $endDate, $branchId) {
                                if ($startDate) {
                                    $reservationQuery->where('start_date', '>=', $startDate);
                                }
                                if ($endDate) {
                                    $reservationQuery->where('end_date', '<=', $endDate);
                                }
                                if ($branchId) {
                                    $reservationQuery->whereHas('branch_product', function ($q) use ($branchId) {
                                        $q->where('branch_id', $branchId);
                                    });
                                }
                            }
                        ]);

                        // Filtrar productos que pertenecen a la sucursal (si está seleccionada)
                        if ($branchId) {
                            $query->whereHas('branches', function ($q) use ($branchId) {
                                $q->where('branches.id', $branchId);
                            });
                        }

                        return $query->orderBy('reservations_count', 'desc');
                    })
                    ->indicateUsing(function (array $data): array {
                        if ($data['value'] ?? null) {
                            $branchName = Branch::find($data['value'])?->name;
                            return $branchName ? ['Sucursal: ' . $branchName] : [];
                        }
                        return [];
                    }),
                ])
                ->actions([])
                ->bulkActions([])
                ->striped()
                ->poll('30s')
                ->defaultSort('reservations_count', 'desc');
    }

    public static function getBaseQuery(): Builder
    {
        return Product::query()
            ->withCount([
                'reservations as reservations_count' => function ($query) {
                    $query->select(\DB::raw('count(*)'));
                }
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductStatistics::route('/'),
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
}
