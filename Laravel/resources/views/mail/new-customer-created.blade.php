<x-mail::message>
    # {{ __('customer/mail.welcome_subject', ['first_name' => $first_name, 'last_name' => $last_name]) }}

    {{ __('customer/mail.welcome_line1') }}
    {{ __('customer/mail.account_created') }}

    {{ __('customer/mail.mail') }} {{ $email }}
    {{ __('customer/mail.temporary_password') }} {!! $password !!}

    {{ __('customer/mail.login_instruction') }}

    {{ __('customer/mail.thanks')}}
</x-mail::message>
