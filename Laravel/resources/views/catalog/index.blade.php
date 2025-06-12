<x-layouts.app>
    <x-slot:title>
        Catálogo
    </x-slot:title>
    <x-catalog.filters />
    <section class="bg-white py-8 md:py-12">
        <div class="mx-auto max-w-screen-xl px-4 2xl:px-0">
            <div class="mb-4 grid gap-4 sm:grid-cols-2 md:mb-8 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($products as $product)
                    <x-catalog.product-card :product="$product"></x-catalog.product-card>
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.app>
