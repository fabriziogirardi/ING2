<x-mail::message>
    # Token de acceso temporal

    Utiliza el siguiente token para ingresar a tu cuenta de manager:

    Token Temporal: {{ $token->token }}

    Este token sera solo valido por los siguientes 2 minutos.

    Gracias,
    {{ config('app.name') }}
</x-mail::message>

