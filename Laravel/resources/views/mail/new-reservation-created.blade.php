<x-mail::message>
    # {{ __('reservation/mail.welcome_title')}}

    {{ __('reservation/mail.code', ['code' => $code])}}

    {{ __('reservation/mail.start_date', ['start_date' => $start_date])}}

    {{ __('reservation/mail.thanks')}}
</x-mail::message>
