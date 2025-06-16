<x-layouts.app>
    <x-slot:title>
        {{ __('catalog/forms.catalog') }}
    </x-slot:title>

<section class="py-8 bg-gray-50 md:py-16 dark:bg-gray-900 antialiased">
    <div class="max-w-screen-xl px-4 mx-auto 2xl:px-0">
        <div class="lg:grid lg:grid-cols-2 lg:gap-8 xl:gap-16">
            <div class="shrink-0 w-full max-w-md lg:max-w-lg mx-auto">
                <div id="default-carousel" class="relative w-full" data-carousel="slide">
                    <!-- Carousel wrapper -->
                    <div class="relative h-56 overflow-hidden rounded-lg md:h-96">
                        @foreach($product->images_json as $image)
                            <div class="hidden duration-700 ease-in-out" data-carousel-item>
                                <img src="{{ Storage::url($image) }}" class="absolute block w-full -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 object-cover" alt="...">
                            </div>
                        @endforeach
                    </div>
                    @if(count($product->images_json) > 1)
                        <div class="absolute top-0 left-0 right-0 bottom-0 bg-gray-900/30"></div>
                        <div class="absolute z-30 flex -translate-x-1/2 bottom-5 left-1/2 space-x-3 rtl:space-x-reverse">
                            @for($i = 0; $i < count($product->images_json); $i++)
                                <button type="button" class="w-3 h-3 rounded-full bg-white/50 hover:bg-white" aria-current="{{ $i === 0 ? 'true' : 'false' }}" aria-label="Imagen {{ $i + 1 }}" data-carousel-slide-to="{{ $i }}"></button>
                            @endfor
                        </div>
                        <button type="button" class="absolute top-0 start-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-prev>
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 1 1 5l4 4"/>
                                </svg>)
                                <span class="sr-only">Anterior</span>
                            </span>
                        </button>
                        <button type="button" class="absolute top-0 end-0 z-30 flex items-center justify-center h-full px-4 cursor-pointer group focus:outline-none" data-carousel-next>
                            <span class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-white/30 dark:bg-gray-800/30 group-hover:bg-white/50 dark:group-hover:bg-gray-800/60 group-focus:ring-4 group-focus:ring-white dark:group-focus:ring-gray-800/70 group-focus:outline-none">
                                <svg class="w-4 h-4 text-white dark:text-gray-800 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                                </svg>
                                <span class="sr-only">Siguiente</span>
                            </span>
                        </button>
                    @endif
                </div>
            </div>

            <div class="mt-6 sm:mt-8 lg:mt-0">
                <h1
                    class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white"
                >
                    {{ $product->name }}
                </h1>
                <div class="mt-4 sm:items-center sm:gap-4 sm:flex">
                    <p
                        class="text-2xl font-extrabold text-gray-900 sm:text-3xl dark:text-white"
                    >
                        ${{ $product->price }} <span class="text-base font-normal text-gray-500 dark:text-gray-400">/ {{ __('catalog/forms.per_day') }}</span>
                    </p>
                </div>
                <div>
                    <h1
                        class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white"
                    >
                        {{ __('catalog/forms.days_to_reserve') }} {{ \Carbon\Carbon::parse($start_date)->diffInDays(\Carbon\Carbon::parse($end_date)) }}
                    </h1>
                </div>


                <div class="mt-6 sm:gap-4 sm:items-center sm:flex sm:mt-8">

                    @if(Auth::getCurrentGuard() === 'customer')
                    <a
                        href="#"
                        title=""
                        class="flex items-center justify-center py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700"
                        role="button"
                    >
                        <x-heroicon-o-heart class="h-6 w-6 me-2" />
                        {{ __('catalog/forms.add_to_wishlist') }}
                    </a>

                    <livewire:payment.mercadopago :branches-with-stock="$branches_with_stock" :start-date="$start_date" :end-date="$end_date" />
                    @endif

                    @if(Auth::getCurrentGuard() === 'employee')
                        <form method="GET" action="{{ route('employee.payment') }}" class="sm:ml-4">
                            @csrf
                            <input type="hidden" name="start_date" value="{{ $start_date }}">
                            <input type="hidden" name="end_date" value="{{ $end_date }}">
                            <input type="hidden" name="branch_product_id" value="{{ session('branch_id') }}">
                            <input type="hidden" name="total_amount" value="{{ $product->price * (\Carbon\Carbon::parse($start_date)->diffInDays(\Carbon\Carbon::parse($end_date)) + 1) }}">
                            <button type="submit" class="flex items-center justify-center py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-primary-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                <i class="fa-solid fa-file-signature mr-2"></i>
                                {{ __('catalog/forms.rent_in_person') }}
                            </button>
                        </form>
                    @endif
{{--                    <a--}}
{{--                        href="#"--}}
{{--                        title=""--}}
{{--                        class="text-white mt-4 sm:mt-0 bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800 flex items-center justify-center"--}}
{{--                        role="button"--}}
{{--                    >--}}
{{--                        <x-heroicon-o-currency-dollar class="h-6 w-6 me-2" />--}}
{{--                        {{ __('catalog/forms.rent') }}--}}
{{--                    </a>--}}
                </div>

                <hr class="my-6 md:my-8 border-gray-200 dark:border-gray-800" />

                <div class="mb-6 text-gray-500">
                    {!! $product->description !!}
                </div>
            </div>
        </div>
    </div>
</section>

</x-layouts.app>
