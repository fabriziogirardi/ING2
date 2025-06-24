<x-mail::message>
    # {{ __('customer/mail.welcome_subject', ['first_name' => $first_name, 'last_name' => $last_name]) }}

    Tus nuevos datos de cuenta son:

    {{ __('customer/mail.mail') }} {{ $email }}
    {{ __('customer/mail.temporary_password') }} {!! $password !!}

    {{ __('customer/mail.login_instruction') }}

    {{ __('customer/mail.thanks')}}
</x-mail::message>
