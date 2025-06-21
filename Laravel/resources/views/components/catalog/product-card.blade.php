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

                @if (Auth::getCurrentGuard() === 'customer')
                <button type="button" data-modal-target="default-modal" data-modal-toggle="default-modal" data-tooltip-target="tooltip-add-to-favorites" class="rounded-lg p-2 text-gray-500 hover:bg-gray-100 hover:text-gray-900">
                    <span class="sr-only"> Agregar a lista de deseados </span>
                    <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6C6.5 1 1 8 5.8 13l6.2 7 6.2-7C23 8 17.5 1 12 6Z" />
                    </svg>
                </button>
                <div id="tooltip-add-to-favorites" role="tooltip" class="tooltip invisible absolute z-10 inline-block rounded-lg bg-gray-900 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-sm transition-opacity duration-300" data-popper-placement="top">
                    Agregar a lista de deseados
                    <div class="tooltip-arrow" data-popper-arrow=""></div>
                </div>
                @endif
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
    </div>
</div>


<!-- Main modal -->
@if (Auth::getCurrentGuard() === 'customer')
<div id="default-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-gray-100/50 backdrop-blur-sm">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">

        <!-- Modal content -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Agregar “{{ $product->name }}” a la lista de deseados</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="default-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <div class="p-4 md:p-5 space-y-4">
        <form action="{{ route('customer.wishlist-item.store') }}" method="POST">
            @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <input type="hidden" name="start_date" value="{{ $startDate }}">
        <input type="hidden" name="end_date" value="{{ $endDate }}">
                <div class="mb-4">
                <label for="wishlist_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Selecciona la lista</label>
                <select id="wishlist_id" name="wishlist_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="">(Seleccione una lista)</option>
                    @foreach($wishlists as $list)
                        <option value="{{ $list->id }}">{{ $list->name }}</option>
                    @endforeach
                </select>
                @error('wishlist_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
                </div>

            <div class="mb-4">
                <label for="sublist_id" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Selecciona la sublista</label>
                <select id="sublist_id" name="wishlist_sublist_id" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                    <option value="">(Seleccione una sublista)</option>
                </select>
                @error('sublist_id')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
            >
                Agregar producto a Sublista
            </button>
            </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Convertimos la colección PHP en un objeto JS:
    const wishlists = @json($wishlists->mapWithKeys(function($list) {
        return [
            $list->id => $list->sublists->map(fn($s) => ['id'=>$s->id,'name'=>$s->name])->toArray()
        ];
    }));

    document.getElementById('wishlist_id').addEventListener('change', function() {
        const subSelect = document.getElementById('sublist_id');
        const selectedList = this.value;

        // Reset options
        subSelect.innerHTML = '<option value="">(Seleccione una sublista)</option>';

        if (wishlists[selectedList]) {
            wishlists[selectedList].forEach(function(s) {
                const opt = document.createElement('option');
                opt.value = s.id;
                opt.text  = s.name;
                subSelect.appendChild(opt);
            });
            subSelect.disabled = false;
        } else {
            subSelect.disabled = true;
        }
    });
</script>
@endif
