<div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
    <x-elements.link-button href="{{ route('customer.logout') }}" text="{{ __('customer/auth.logout_button') }}"
        icon-left="fa-solid fa-right-from-bracket" type="danger" class="order-1 lg:order-2" />
    <x-navigation.navbar.account-badge :person="auth()->guard('customer')->user()->person" user-type="{{ __('auth.role_customer') }}" class="order-2 lg:order-1" />
</div>
