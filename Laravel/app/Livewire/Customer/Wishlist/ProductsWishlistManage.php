<?php

namespace App\Livewire\Customer\Wishlist;

use App\Models\Wishlist;
use App\Models\WishlistProduct;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;

class ProductsWishlistManage extends Component implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    public Wishlist $wishlist;

    public function mount(Wishlist $wishlist)
    {
        $this->wishlist = $wishlist;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                WishlistProduct::where('wishlist_id', $this->wishlist->id)
                    ->whereHas('product')
            )
            ->columns([
                TextColumn::make('product.name')
                    ->label('Nombre de Maquinaria'),
                TextColumn::make('product.price')
                    ->label('Precio por dia'),
                TextColumn::make('start_date')
                    ->label('Fecha de inicio')
                    ->date('d-m-Y'),
                TextColumn::make('end_date')
                    ->label('Fecha de finalización')
                    ->date('d-m-Y'),
            ])
            ->headerActions([
                Action::make('back_to_wishlist')
                    ->label('Volver')
                    ->icon('heroicon-m-arrow-left')
                    ->color('info')
                    ->url(route('customer.wishlist.index')),
            ])
            ->actions([
                Action::make('Reservar')
                    ->button()
                    ->color(fn ($record) => $this->isReservable($record) ? 'info' : 'gray')
                    ->label(fn ($record) => $this->isReservable($record)
                        ? 'Reservar'
                        : $this->getUnavailabilityReason($record)
                    )
                    ->tooltip(fn ($record) => ! $this->isReservable($record) ? $this->getUnavailabilityReason($record) : null)
                    ->disabled(fn ($record) => ! $this->isReservable($record))
                    ->action(function ($record) {
                        if ($this->isReservable($record)) {
                            session([
                                'start_date' => $record->start_date,
                                'end_date'   => $record->end_date,
                            ]);

                            return redirect()->route('catalog.show', ['product' => $record->product_id]);
                        }
                    }),

                Action::make('delete')
                    ->icon('heroicon-m-trash')
                    ->iconButton()
                    ->color('danger')
                    ->requiresConfirmation()
                    ->action(fn (WishlistProduct $record) => $record->delete()),
            ]);
    }

    protected function isReservable(WishlistProduct $record): bool
    {
        return $this->getUnavailabilityReason($record) === null;
    }

    protected function getUnavailabilityReason(WishlistProduct $record): ?string
    {
        $start_date = $record->start_date;
        $end_date   = $record->end_date;

        $today = \Carbon\Carbon::today()->toDateString();

        if ($record->product && method_exists($record->product, 'trashed') && $record->product->trashed()) {
            return 'Maquinaria no vigente';
        }

        if (\Carbon\Carbon::parse($start_date)->lt(\Carbon\Carbon::parse($today))) {
            return 'La fecha de inicio ya no es válida';
        }

        $days     = \Carbon\Carbon::parse($start_date)->diffInDays(\Carbon\Carbon::parse($end_date)) + 1;
        $min_days = $record->product->min_days;
        if ($days < $min_days) {
            return "La reserva debe ser de al menos $min_days días";
        }

        $service  = new \App\Services\ProductAvailabilityService($start_date, $end_date);
        $products = $service->getProductsWithAvailability(100);

        $productData = $products->getCollection()->first(
            fn ($item) => $item['product']->id === $record->product_id
        );

        if (! $productData || ! $productData['has_stock']) {
            return 'No hay stock actualmente';
        }

        return null;
    }

    public function render()
    {
        return view('livewire.wishlist.productswishlist');
    }
}
