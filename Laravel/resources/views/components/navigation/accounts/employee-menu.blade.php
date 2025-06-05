<div class="flex flex-col md:flex-row flex-grow-0">
    <x-elements.link-button href="{{ route('employee.logout') }}" text="{{ __('employee/auth.logout_button') }}"
                            icon-left="fa-solid fa-right-from-bracket" type="danger" />
    <x-navigation.navbar.account-badge :person="auth()->guard('employee')->user()->person" user-type="{{ __('auth.role_employee') }}" />
</div>
