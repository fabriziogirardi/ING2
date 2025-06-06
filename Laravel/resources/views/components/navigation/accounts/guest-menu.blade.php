<div {{ $attributes->merge(['class' => 'gap-2']) }}>
    <x-elements.link-button href="{{ route('employee.login') }}" text="{{ __('home.employee_login') }}" icon-left="fa-solid fa-user-tie" />
    <x-elements.link-button href="{{ route('customer.login') }}" text="{{ __('home.customer_login') }}" icon-left="fa-solid fa-user" />
</div>
