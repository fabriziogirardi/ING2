<?php

namespace App\View\Components\Payment;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MercadopagoButton extends Component
{
    /**
     * Create a new component instance.
     *
     * @throws \MercadoPago\Exceptions\MPApiException
     */
    public function __construct(
        public string $publicKey,
        public string $preferenceId,
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.payment.mercadopago-button', [
            'publicKey'    => $this->publicKey,
            'preferenceId' => $this->preferenceId,
        ]);
    }
}
