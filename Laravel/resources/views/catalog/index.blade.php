<x-layouts.app>
    <x-slot:title>
        {{ __('catalog/forms.catalog') }}
    </x-slot:title>
    <x-catalog.filters :start_date="$start_date" :end_date="$end_date" />
    <section class="bg-white py-8 md:py-12">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
            @if(!$start_date || !$end_date)
                <div class="flex justify-center items-center h-64">
                    <h2 class="text-2xl font-bold text-gray-400 text-center">
                        {{ __('catalog/forms.select_dates_to_see_availability') }}
                    </h2>
                </div>
            @elseif($products && $products->count())
                <div class="mb-4 grid gap-4 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach($products as $item)
                        <x-catalog.product-card
                            :product="$item['product']"
                            :has_stock="$item['has_stock']"
                            :meets_min_days="$item['meets_min_days']"
                            :min_days="$item['min_days']"
                            :start_date="$start_date"
                            :end_date="$end_date"
                        />
                    @endforeach
                </div>
            @else
                <div class="flex justify-center items-center h-64">
                    <h2 class="text-2xl font-bold text-gray-400 text-center">
                        {{ __('catalog/forms.no_products_available') }}
                    </h2>
                </div>
            @endif
        </div>
    </section>
</x-layouts.app>
