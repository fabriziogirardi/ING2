<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\MercadoPagoAuthenticateRequest;
use App\Models\BranchProduct;
use App\Models\Reservation;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

class MercadoPagoController extends Controller
{
    public function show(MercadoPagoAuthenticateRequest $request)
    {
        $requestData = $request->validated();

        MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));

        $client = new PreferenceClient;

        $product = BranchProduct::find($request->validated('branch_product_id'))->product;

        $code = Str::of(Str::random(8))->upper();

        while (Reservation::where('code', $code)->exists()) {
            $code = Str::of(Str::random(8))->upper();
        }

        URL::forceHttps();

        $linkSucess = URL::signedRoute(
            'customer.reservation.store',
            [
                'branch_product_id' => $requestData['branch_product_id'],
                'customer_id'       => auth()->user()->id,
                'start_date'        => $requestData['start_date'],
                'end_date'          => $requestData['end_date'],
                'code'              => $code,
                'total_amount'      => $requestData['total_amount'],
            ],
        );

        $preference = $client->create([
            'items' => [
                [
                    'title'      => "Reserva de la maquinaria: {$product->name}",
                    'quantity'   => 1,
                    'unit_price' => (float) $requestData['total_amount'],
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
                'success' => $linkSucess,
                'failure' => 'https://42c9-181-171-172-58.ngrok-free.app/customer/reservations/failure',
            ],
            'external_reference' => $request->validated('branch_product_id'),
            'auto_return'        => 'approved',
        ]);

        $preference->auto_return = 'approved';

        $publicKey = config('services.mercadopago.public_key');

        return view('payment.mp-payment', [
            'preferenceId' => $preference->id,
            'publicKey'    => $publicKey,
            'requestData'  => $requestData,
            'productName'  => $product->name,
        ]);
    }
}
