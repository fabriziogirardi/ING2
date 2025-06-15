@props(['product', 'has_stock', 'meets_min_days', 'min_days', 'start_date', 'end_date'])

@php
    $is_available = $has_stock && $meets_min_days;
@endphp

<div class="rounded-lg border border-blue-200 bg-white p-6 shadow-md hover:shadow-lg transition-shadow duration-300 ease-in-out hover:shadow-blue-400">
    <div class="w-full pb-4 relative h-64">
        <img
            @class([
                'mx-auto',
                'h-full',
                'w-64 object-contain',
                'grayscale' => !$is_available
            ])
            src="{{ $product->getFirstImage() }}"
            alt="{{ $product->name }}"
        />
    </div>
    <div class="flex flex-col gap-y-2">
        <h1 class="text-lg leading-tight font-bold text-gray-900">{{ $product->name }}</h1>
        @if($is_available)
            <a href="{{ route('catalog.show', $product) }}" class="text-blue-400 hover:text-white border border-blue-400 hover:bg-blue-500 focus:ring-4 focus:outline-none focus:ring-yellow-200 font-semibold rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                {{ __('catalog/forms.see_more') }}
            </a>
        @elseif(!$meets_min_days)
            <span class="text-gray-400 border border-gray-300 bg-gray-100 font-semibold rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 cursor-not-allowed">
                {{ __('catalog/forms.min_days_to_reserve', ['days' => $min_days]) }}
            </span>
        @else
            <span class="text-gray-400 border border-gray-300 bg-gray-100 font-semibold rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 cursor-not-allowed">
                {{ __('catalog/forms.not_available') }}
            </span>
        @endif
    </div>
</div>
