@props(['label', 'name', 'type'])

@php
    $defaults = [
        'type' => $type,
        'id' => $name,
        'name' => $name,
        'class' => 'bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5',
        'value' => old($name)
    ];
@endphp

<x-forms.field :$label :$name :$type>
    <input {{ $attributes($defaults) }}>
</x-forms.field>
