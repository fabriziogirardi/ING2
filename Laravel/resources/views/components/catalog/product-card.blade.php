<div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
    <div class="h-56 w-full">
        <a href="#">
            <img class="mx-auto h-full" src="{{ Storage::url($product->images_json[0]) }}" alt="" />
        </a>
    </div>
    <div class="pt-6">
        <div class="mb-4 flex items-center justify-between gap-4">
            <span class="me-2 rounded bg-primary-100 px-2.5 py-0.5 text-xs font-medium text-primary-800 ">DESCUENTO FUTURO</span>

            <div class="flex items-center justify-end gap-1">
                <div id="tooltip-quick-look" role="tooltip" class="tooltip invisible absolute z-10 inline-block rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-sm transition-opacity duration-300" data-popper-placement="top">
                    Quick look
                    <div class="tooltip-arrow" data-popper-arrow=""></div>
                </div>

                <button type="button" data-tooltip-target="tooltip-add-to-favorites" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900">
                    <span class="sr-only"> Add to Favorites </span>
                    <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6C6.5 1 1 8 5.8 13l6.2 7 6.2-7C23 8 17.5 1 12 6Z" />
                    </svg>
                </button>
                <div id="tooltip-add-to-favorites" role="tooltip" class="tooltip invisible absolute z-10 inline-block rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-sm transition-opacity duration-300" data-popper-placement="top">
                    Add to favorites
                    <div class="tooltip-arrow" data-popper-arrow=""></div>
                </div>
            </div>
        </div>

        <a href="#" class="text-lg font-semibold leading-tight text-gray-900 hover:underline">{{ $product->name }}</a>

        <ul class="mt-2 flex items-center gap-4">
            <li class="flex items-center gap-2">
                <svg class="h-4 w-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h6l2 4m-8-4v8m0-8V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v9h2m8 0H9m4 0h2m4 0h2v-4m0 0h-5m3.5 5.5a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Zm-10 0a2.5 2.5 0 1 1-5 0 2.5 2.5 0 0 1 5 0Z" />
                </svg>
                <p class="text-sm font-medium text-gray-500">Fast Delivery</p>
            </li>

            <li class="flex items-center gap-2">
                <svg class="h-4 w-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M8 7V6c0-.6.4-1 1-1h11c.6 0 1 .4 1 1v7c0 .6-.4 1-1 1h-1M3 18v-7c0-.6.4-1 1-1h11c.6 0 1 .4 1 1v7c0 .6-.4 1-1 1H4a1 1 0 0 1-1-1Zm8-3.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
                </svg>
                <p class="text-sm font-medium text-gray-500">Best Price</p>
            </li>
        </ul>

        <div class="mt-4 items-center justify-between gap-4">
            @if($productData['has_stock'])
                @if ((Auth::getCurrentGuard() === 'customer' || $hasStockInBranch))
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
            @else
                <span class="inline-flex items-center w-full justify-center rounded-lg bg-gray-100 px-5 py-2.5 text-sm font-medium text-gray-400 border border-gray-300 cursor-not-allowed">
                {{ __('catalog/forms.not_available') }}
                </span>
            @endif
        </div>
    </div>
</div>
