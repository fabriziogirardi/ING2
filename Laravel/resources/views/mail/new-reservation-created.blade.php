<x-mail::message>
    # {{ __('reservation/mail.welcome_title')}}

    {{ __('reservation/mail.code', ['code' => $code])}}

    {{ __('reservation/mail.start_date', ['start_date' => $start_date])}}

    -----------------------------------------------------------------------
                                    Factura

    Metodo de Pago: {{ $method }}

    Total Pagado: ${{ $total_amount }}

    -----------------------------------------------------------------------

    {{ __('reservation/mail.thanks')}}
</x-mail::message>
