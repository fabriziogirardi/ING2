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
use Illuminate\Support\HtmlString;

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
                            ->placeholder('Seleccionar fecha inicio')
                            ->default(static::getFilterValue('date_range', 'start_date'))
                            ->live(),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Fecha Fin')
                            ->placeholder('Seleccionar fecha fin')
                            ->default(static::getFilterValue('date_range', 'end_date'))
                            ->live(),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query;
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
                    ->default(static::getFilterValue('branch_id', 'value'))
                    ->query(function (Builder $query, array $data): Builder {
                        return $query;
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
            ->defaultSort('reservations_count', 'desc')
            ->persistFiltersInSession()
            ->headerActions([]);
    }

    public static function getBaseQuery(): Builder
    {
        // Obtener filtros desde la request
        $filters = request()->get('tableFilters', []);
        $startDate = $filters['date_range']['start_date'] ?? null;
        $endDate = $filters['date_range']['end_date'] ?? null;
        $branchId = $filters['branch_id']['value'] ?? null;

        $query = Product::query();

        // Aplicar withCount con filtros desde el inicio
        $query->withCount([
            'reservations as reservations_count' => function ($reservationQuery) use ($startDate, $endDate, $branchId) {
                $reservationQuery->withTrashed();

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

        return $query
            ->with(['product_model.brand', 'categories'])
            ->orderBy('reservations_count', 'desc');
    }

    private static function getFilterValue(string $filterName, string $key = null): mixed
    {
        $filters = request()->get('tableFilters', []);

        if ($key) {
            return $filters[$filterName][$key] ?? null;
        }

        return $filters[$filterName] ?? null;
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
