<?php

namespace App\View\Components\Payment;

use App\Models\BranchProduct;
use App\Models\Reservation;
use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

class MercadopagoButton extends Component
{
    /**
     * The public key for Mercado Pago.
     */
    public string $publicKey;
    
    /**
     * The preference ID for the payment.
     */
    public string $preferenceId;
    /**
     * Create a new component instance.
     *
     * @throws \MercadoPago\Exceptions\MPApiException
     */
    public function __construct(
        public string $branchProductId,
        public Carbon $startDate,
        public Carbon $endDate,
    ) {
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));
        
        $product = BranchProduct::find($this->branchProductId)->product;
        
        do {
            $code = Str::of(Str::random(8))->upper();
        } while (Reservation::where('code', $code)->exists());
        
        $startDate = $this->startDate->format('Y-m-d');
        $endDate   = $this->endDate->format('Y-m-d');
        $totalPrice = $product->price * $this->startDate->diffInDays($this->endDate) + 1;
        
        $linkSuccess = URL::signedRoute(
            'customer.reservation.store',
            [
                'branch_product_id' => $this->branchProductId,
                'customer_id'       => auth()->user()->id,
                'start_date'        => $startDate,
                'end_date'          => $endDate,
                'code'              => $code,
                'total_amount'      => $totalPrice,
            ],
            absolute: false,
        );
        
        $preference = (new PreferenceClient())->create([
            'items' => [
                [
                    'title'      => "Reserva de la maquinaria: {$product->name}",
                    'quantity'   => 1,
                    'unit_price' => (float) $totalPrice,
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
                'success' => 'https://d817-181-23-54-79.ngrok-free.app'.$linkSuccess,
                'failure' => 'https://d817-181-23-54-79.ngrok-free.app/customer/reservations/failure',
            ],
            'external_reference' => $this->branchProductId,
            'auto_return'        => 'approved',
        ]);
        
        $preference->auto_return = 'approved';
        
        $this->publicKey = config('services.mercadopago.public_key');
        $this->preferenceId = $preference->id;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.payment.mercadopago-button');
    }
}
