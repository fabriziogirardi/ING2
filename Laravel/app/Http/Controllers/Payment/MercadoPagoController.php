<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\MercadoPagoAuthenticateRequest;
use MercadoPago\Client\Preference\PreferenceClient;
use MercadoPago\MercadoPagoConfig;

class MercadoPagoController extends Controller
{
    public function show(MercadoPagoAuthenticateRequest $request)
    {
        MercadoPagoConfig::setAccessToken(config('services.mercadopago.token'));
        $client = new PreferenceClient;

        $preference = $client->create([
            'items' => [
                [
                    'title'      => $request->validated('title'),
                    'quantity'   => 1,
                    'unit_price' => (float) $request->validated('unit_price'),
                ],
            ],
            'excluded_payment_types' => [
                [
                    'id' => 'ticket',
                ],
            ],
            'back_urls' => [
                'success' => 'https://19e9-2802-8012-f83-801-7072-e76d-c997-2158.ngrok-free.app/payment/success',
                'failure' => 'https://19e9-2802-8012-f83-801-7072-e76d-c997-2158.ngrok-free.app/payment/failure',
                'pending' => 'https://19e9-2802-8012-f83-801-7072-e76d-c997-2158.ngrok-free.app/payment/pending',
            ],
            'auto_return' => 'approved',
        ]);

        $preference->auto_return = 'approved';

        $publicKey = config('services.mercadopago.public_key');

        return view('payment.mp-payment', [
            'preferenceId' => $preference->id,
            'publicKey'    => $publicKey,
        ]);
    }
}
