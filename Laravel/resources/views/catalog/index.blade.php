<x-layouts.app>
    <x-slot:title>
        {{ __('catalog/forms.catalog') }}
    </x-slot:title>

    <section class="bg-gray-50 py-8 antialiased dark:bg-gray-900 md:py-1">

            <div class="mx-auto max-w-3xl px-4 text-center sm:px-6 lg:max-w-7xl lg:px-8">
{{--                <div class="py-8">--}}
{{--                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ __('catalog/forms.catalog_title') }}</h1>--}}
{{--                    <h2 class="mt-2">{{ __('catalog/forms.catalog_subtitle') }}</h2>--}}
{{--                </div>--}}
                <h2 id="filter-heading" class="sr-only">Product filters</h2>
                <form action="" method="GET">
                    <div class="flex flex-row justify-between items-center py-4">
                        <h1 class="font-bold text-lg">{{ __('catalog/forms.rental_date') }}</h1>
                        <div id="date-range-picker" date-rangepicker class="flex items-center">
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                    </svg>
                                </div>
                                <input id="datepicker-range-start" datepicker datepicker-min-date="{{ now()->format("m/d/Y") }}" name="start_date" type="text"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="{{ __('catalog/forms.select_date_start') }}"
                                       value="{{ $start_date }}"
                                >
                            </div>
                            <span class="mx-4 text-gray-500">{{ __('catalog/forms.to') }}</span>
                            <div class="relative">
                                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                    </svg>
                                </div>
                                <input id="datepicker-range-end" datepicker datepicker-min-date="{{ now()->format("m/d/Y") }}" name="end_date" type="text"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                       placeholder="{{ __('catalog/forms.select_date_end') }}"
                                       value="{{ $end_date }}"
                                >
                            </div>
                        </div>
                        <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-400 border border-transparent rounded-md shadow-sm hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <i class="fa-solid fa-magnifying-glass mr-2"></i>
                            {{ __('catalog/forms.search') }}
                        </button>
                    </div>
                </form>
            </div>

        <section aria-labelledby="filter-heading" class="border-t border-gray-200">
            <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
                <!-- Heading & Filters -->
                <div class="mb-4 items-end justify-between space-y-4 sm:flex sm:space-y-0 md:mb-8">
                    <div>
                        <h2 class="mt-3 text-xl font-semibold text-gray-900 sm:text-2xl">Catalogo</h2>
                    </div>
                </div>
                <div class="mb-4 grid gap-4 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach($products as $item)
                        <x-catalog.product-card
                            :productData="$item"
                            :start-date="$start_date"
                            :end-date="$end_date"
                        />
                    @endforeach
                </div>
                <div class="w-full text-center">
                    <button type="button" class="rounded-lg border border-gray-200 bg-white px-5 py-2.5 text-sm font-medium text-gray-900 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:outline-none focus:ring-4 focus:ring-gray-100 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white dark:focus:ring-gray-700">Show more</button>
                </div>
            </div>
        </section>
</section>

<!-- Main modal -->
    @if (Auth::getCurrentGuard() === 'customer')
        <div id="default-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-gray-100/50 backdrop-blur-sm">
            <div class="relative p-4 w-full max-w-2xl max-h-full">
                <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                        <h3>Agregar a la lista de deseados</h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="default-modal">
                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                            </svg>
                            <span class="sr-only">Close modal</span>
                        </button>
                    </div>
                    <div class="p-4 md:p-5 space-y-4">
                        <form action="{{ route('customer.wishlist-product.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="product_id" value="">
                            <input type="hidden" name="start_date" value="{{ $start_date }}">
                            <input type="hidden" name="end_date" value="{{ $end_date }}">
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
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                                Agregar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.querySelectorAll('[data-modal-toggle="default-modal"]').forEach(btn => {
                btn.addEventListener('click', function() {
                    const productId = this.getAttribute('data-product-id');
                    document.querySelector('#default-modal input[name="product_id"]').value = productId;
                });
            });

            document.getElementById('wishlist_id').addEventListener('change', function() {
                const subSelect = document.getElementById('sublist_id');
                const selectedList = this.value;
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


</x-layouts.app>
