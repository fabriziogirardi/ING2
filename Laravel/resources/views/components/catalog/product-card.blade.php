<div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
    <div class="h-56 w-full">
        <a href="#">
            <img class="mx-auto h-full" src="{{ Storage::url($product->images_json[0]) }}" alt="" />
        </a>
    </div>
    <div class="pt-6">
        <div class="mb-4 flex items-center justify-between gap-4">
            @if ($coupon)
                <span class="me-2 rounded bg-green-100 px-2.5 py-0.5 text-xs font-medium text-green-800 ">
                    {{ $coupon->discount_percentage }}% {{ __('Descuento') }}
                </span>
            @endif
            <div class="flex items-center justify-end gap-1">
                <div id="tooltip-quick-look" role="tooltip" class="tooltip invisible absolute z-10 inline-block rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-sm transition-opacity duration-300" data-popper-placement="top">
                    Quick look
                    <div class="tooltip-arrow" data-popper-arrow=""></div>
                </div>
            </div>
        </div>
        <div class="flex items-center justify-between">
            <a href="#" class="text-lg font-semibold leading-tight text-gray-900 hover:underline">{{ $product->name }}</a>
            @if (Auth::getCurrentGuard() === 'customer')
                <div class="relative flex items-center">
                    <button type="button" data-modal-target="default-modal" data-modal-toggle="default-modal" data-tooltip-target="tooltip-add-to-favorites" data-product-id="{{ $product->id }}" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900">
                        <span class="sr-only"> Agregar a lista de deseados </span>
                        <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6C6.5 1 1 8 5.8 13l6.2 7 6.2-7C23 8 17.5 1 12 6Z" />
                        </svg>
                    </button>
                    <div id="tooltip-add-to-favorites" role="tooltip" class="tooltip invisible absolute z-10 inline-block rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-sm transition-opacity duration-300" data-popper-placement="top">
                        Agregar a lista de deseados
                        <div class="tooltip-arrow" data-popper-arrow=""></div>
                    </div>
                </div>
            @endif
        </div>
        <div class="mt-2 flex flex-wrap gap-2">
            @foreach($product->categories as $category)
                <div class="flex items-center gap-1 bg-primary-50 rounded px-2 py-0.5 mb-1 max-w-full">
                    <svg class="h-3 w-3 text-primary-500 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2" fill="currentColor" />
                    </svg>
                    <span class="text-xs font-medium text-gray-700 truncate">{{ $category->name }}</span>
                </div>
            @endforeach
        </div>
    @if(Auth::getCurrentGuard() === 'employee' || Auth::getCurrentGuard() === 'customer')
    <div class="mt-4 items-center justify-between gap-4">
        @if($productData['has_stock'])
            @if($meetsMinDays)
                <a href="{{ route('catalog.show', $product) }}" class="inline-flex items-center w-full justify-center rounded-lg bg-primary-700 px-5 py-2.5 text-sm font-medium text-white hover:bg-primary-800 focus:outline-none focus:ring-4 focus:ring-primary-300">
                    <i class="fa fa-search me-2 align-middle"></i>
                    Ver detalles
                </a>
            @else
                <span class="inline-flex items-center w-full justify-center rounded-lg bg-gray-100 px-5 py-2.5 text-sm font-medium text-gray-400 border border-gray-300 cursor-not-allowed">
                    {{ __('catalog/forms.min_days_to_reserve', ['days' => $product['min_days']]) }}
                </span>
            @endif
        @else
            <span class="inline-flex items-center w-full justify-center rounded-lg bg-gray-100 px-5 py-2.5 text-sm font-medium text-gray-400 border border-gray-300 cursor-not-allowed">
                {{ __('catalog/forms.not_available') }}
            </span>
        @endif
    </div>
    @endif
    </div>
</div>
