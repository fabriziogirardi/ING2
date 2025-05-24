<x-errors.generic>
    <x-slot:title>
        {{ __('errors/503.title') }}
    </x-slot>

    <x-slot:image>
        {{ asset('img/503-broken.png') }}
    </x-slot>

    <x-slot:code>
        {{ __('errors/503.code') }}
    </x-slot>

    <x-slot:description>
        {{ __('errors/503.description') }}
    </x-slot>

    <x-slot:action>
        <a href="{{ url('/') }}" class="text-blue-500 hover:text-blue-700">Go back to home</a>
    </x-slot>
</x-errors.generic>
