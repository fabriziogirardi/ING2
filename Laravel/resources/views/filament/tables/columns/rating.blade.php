@php
    $state = $getState();
@endphp

<div class="flex">
    @if(blank($state))
        <x-filament-tables::columns.placeholder>
            Not Rated
        </x-filament-tables::columns.placeholder>
    @else
        @for($i = 1; $i <= 5; $i++)
            <div
                    @class([
                        'text-gray-200' => $state < $i,
                        'text-primary-500' => $state >= $i,
                    ])
            >
                <x-icon name="heroicon-s-star" class="w-6 h-6 pointer-events-none" />
            </div>
        @endfor
    @endif
</div>
