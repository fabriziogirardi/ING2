<?php

namespace App\Livewire\Payment;

use App\Models\BranchProduct;
use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Livewire\Component;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

class Mercadopago extends Component
{
    public array $branchesWithStock;

    public string $branchProductId = '0';

    public string $startDate;

    public string $endDate;

    public string $publicKey;

    public string $preferenceId;

    public string $totalPrice;

    public string $code;

    public function mount(array $branchesWithStock, string $startDate, string $endDate): void
    {
        $this->branchesWithStock = $branchesWithStock;
        $this->startDate         = $startDate;
        $this->endDate           = $endDate;
        $this->publicKey         = config('services.mercadopago.public_key');
        $this->preferenceId      = '';
    }

    public function generateButton(): void
    {
        if (! $this->branchProductId) {
            return;
        }

        MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));

        $product = BranchProduct::find($this->branchProductId)->product;

        do {
            $this->code = Str::of(Str::random(8))->upper();
        } while (Reservation::where('code', $this->code)->exists());

        $startDate = Carbon::parse($this->startDate);
        $endDate   = Carbon::parse($this->endDate);

        $customer   = auth('customer')->user();
        $hasPenalty = $customer && $customer->has_penalization;

        $days      = $startDate->diffInDays($endDate) + 1;
        $basePrice = $product->price * $days;

        $coupon = $customer?->coupon;
        if ($coupon) {
            $basePrice -= $basePrice * ($coupon->discount_percentage / 100);
        }

        $this->totalPrice = $hasPenalty ? round($basePrice * 1.1, 2) : $basePrice;

        $startDate->format('Y-m-d');
        $endDate->format('Y-m-d');

        $linkSuccess = URL::signedRoute(
            'customer.reservation.store',
            [
                'branch_product_id' => $this->branchProductId,
                'customer_id'       => auth('customer')->user()->id,
                'start_date'        => $startDate,
                'end_date'          => $endDate,
                'code'              => $this->code,
                'total_amount'      => $this->totalPrice,
            ],
            absolute: false,
        );

        $preference = (new PreferenceClient)->create([
            'items' => [
                [
                    'title'      => "Reserva de la maquinaria: {$product->name}",
                    'quantity'   => 1,
                    'unit_price' => (float) $this->totalPrice,
                ],
            ],
            'payment_methods' => [
                'excluded_payment_types' => [
                    [
                        'id' => 'ticket',
                    ],
                ],
            ],
            'back_urls' => [
                'success' => 'https://alkilar.com.ar'.$linkSuccess,
                'failure' => 'https://alkilar.com.ar/customer/reservations/failure',
            ],
            'external_reference' => $this->branchProductId,
            'auto_return'        => 'approved',
        ]);

        $preference->auto_return = 'approved';

        $this->preferenceId = $preference->id;

        $this->dispatch('algo', ['publicKey' => $this->publicKey, 'preferenceId' => $this->preferenceId]);
    }

    public function render(): View|Application|Factory
    {
        return view('livewire.payment.mercadopago');
    }
}
