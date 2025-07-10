@php
    $state = $getState();
    $fullStars = floor($state);
    $hasPartial = ($state - $fullStars) > 0;
    $partialPercent = ($state - $fullStars) * 100;
    $emptyStars = 5 - $fullStars - ($hasPartial ? 1 : 0);
@endphp

<div {{ $attributes->merge(['class' => 'flex items-center']) }}>
    @if(blank($state))
        <x-filament-tables::columns.placeholder>
            <i>No disponible</i>
        </x-filament-tables::columns.placeholder>
    @else
        {{-- Estrellas completamente llenas --}}
        @for($i = 0; $i < $fullStars; $i++)
            <div class="relative w-6 h-6">
                <x-icon name="heroicon-s-star" class="w-6 h-6 text-primary-500 pointer-events-none" />
            </div>
        @endfor

        {{-- Estrella parcialmente llena --}}
        @if($hasPartial)
            <div class="relative w-6 h-6">
                <div class="relative w-6 h-6">
                    {{-- Estrella vacía de fondo --}}
                    <x-icon name="heroicon-s-star" class="w-6 h-6 text-gray-200 pointer-events-none absolute" />
                    {{-- Estrella llena con clip para mostrar solo el porcentaje --}}
                    <div class="absolute inset-0 overflow-hidden"
                         style="clip-path: inset(0 {{ 100 - $partialPercent }}% 0 0);">
                        <x-icon name="heroicon-s-star" class="w-6 h-6 text-primary-500 pointer-events-none" />
                    </div>
                </div>
            </div>
        @endif

        {{-- Estrellas vacías --}}
        @for($i = 0; $i < $emptyStars; $i++)
            <div class="relative w-6 h-6">
                <x-icon name="heroicon-s-star" class="w-6 h-6 text-gray-200 pointer-events-none" />
            </div>
        @endfor

        {{-- Mostrar el valor numérico opcionalmente --}}
        <span class="ml-2 text-sm text-gray-500 font-mono">{{ number_format($state, 2) }}</span>
    @endif
</div>
