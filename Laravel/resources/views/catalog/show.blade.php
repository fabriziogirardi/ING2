<x-layouts.app>
    <x-slot:title>
        {{ $product->name }}
    </x-slot:title>
    <section class="h-screen bg-white md:py-16 flex items-center mb-16">
        <div class="max-w-screen-xl px-4 mx-auto 2xl:px-0">
            <div class="lg:grid lg:grid-cols-2 lg:gap-8 xl:gap-16 items-center justify-center">
                <div id="controls-carousel" class="relative w-full max-w-lg mx-auto" data-carousel="slide">
                    <!-- Carousel wrapper -->
                    <div class="relative aspect-square overflow-hidden rounded-lg">
                        <!-- Item 1 -->
                        @foreach($product->getImages() as $image)
                            <div class="hidden duration-700 ease-in-out" data-carousel-item>
                                <img src="{{ $image }}" class="absolute block w-full h-full object-contain -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
                            </div>
                        @endforeach
                    </div>
                    <!-- Slider controls -->
                    <button type="button" class="absolute top-0 -start-10 z-30 flex items-center justify-center h-full px-4 group" data-carousel-prev>
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full cursor-pointer group-hover:bg-blue-400/50 active:ring-4 active:ring-yellow-200 transition-colors duration-300 ease-in-out">
                            <i class="fa-solid fa-chevron-left"></i>
                            <span class="sr-only">Previous</span>
                        </span>
                    </button>
                    <button type="button" class="absolute top-0 -end-10 z-30 flex items-center justify-center h-full px-4 group" data-carousel-next>
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full group-hover:bg-blue-400/50 cursor-pointer active:ring-4 active:ring-yellow-200 transition-colors duration-300 ease-in-out">
                            <i class="fa-solid fa-chevron-right"></i>
                            <span class="sr-only">Next</span>
                        </span>
                    </button>
                </div>

                <div class="mt-6 sm:mt-8 lg:mt-0 h-screen bg-gray-50 px-8">
                    <div class="flex flex-col justify-center items-center h-full">
                        <div class="flex flex-row flex-wrap pb-6 justify-center gap-x-2">
                            @foreach($product->categories as $category)
                                <a href="#" class="border-2 rounded-full px-2 py-0.5 border-yellow-400 text-yellow-400 font-bold">{{ $category->name }}</a>
                            @endforeach
                        </div>

                        <div class="flex items-center gap-x-3 pb-4 justify-center">
                            <p>{{ $product->product_model->brand->name }}</p>
                            <p>|</p>
                            <p>{{ $product->product_model->name }}</p>
                        </div>
                        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl flex justify-center">
                            {{ $product->name }}
                        </h1>
                        <p class="mb-2 text-gray-500 flex justify-center">
                            {!! html_entity_decode($product->description) !!}
                        </p>

                        <div class="border-t border-gray-200 w-full"></div>

                        <form method="GET" action="{{ route('customer.payment') }}">
                            @csrf
                            <div class="grid grid-cols-3 gap-x-4 pt-4 items-center grid-flow-col">

                                <input type="hidden" name="start_date" value="{{ $start_date }}">
                                <input type="hidden" name="end_date" value="{{ $end_date }}">

                                <div>
                                    <button data-dropdown-toggle="dropdown" class="text-gray-700 hover:text-white bg-gray-100 hover:bg-blue-400 active:ring-4 active:outline-none active:ring-yellow-200 font-medium rounded-lg text-sm px-5 py-1 text-center flex flex-row items-center" type="button">
                                        <i class="fa-solid fa-shop mr-2"></i>
                                        Sucursales con Stock
                                        <i class="fa-solid fa-chevron-down ml-2"></i>
                                    </button>

                                    <!-- Dropdown menu -->
                                    <div id="dropdown" class="z-10 hidden bg-white divide-y divide-blue-300 rounded-lg shadow-sm w-44">
                                        <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdownDelayButton">
                                            @foreach($product->branchesWithStockBetween($start_date, $end_date) as $id => $branchName)
                                                @php
                                                    $branchProduct = App\Models\BranchProduct::where('branch_id', $id)
                                                        ->where('product_id', $product->id)
                                                        ->first();
                                                @endphp
                                                @if($branchProduct)
                                                    <li class="flex items-center justify-between px-4 py-2 hover:bg-blue-300">
                                                        <div class="flex items-center w-full">
                                                            <input
                                                                type="radio"
                                                                id="branch_{{ $id }}"
                                                                value="{{ $branchProduct->id }}"
                                                                name="branch_product_id"
                                                                wire:model="selectedBranchProduct"
                                                                class="hover:bg-blue-300 text-blue-400"
                                                            >
                                                            <label for="branch_{{ $id }}" class="flex-1 ml-3">{{ $branchName }}</label>
                                                        </div>
                                                    </li>
                                                @endif
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>

                                <input type="hidden" name="total_amount" value="{{ $product->price * \Carbon\Carbon::parse($start_date)->diffInDays(\Carbon\Carbon::parse($end_date)) }}">

                                <div class="col-span-2">
                                    <button type="submit" class="inline-flex items-center justify-center w-full text-lg font-bold text-white bg-blue-400 hover:bg-blue-500 focus:ring-4 focus:outline-none focus:ring-yellow-200 rounded-lg py-2.5 text-center transition-colors duration-300 ease-in-out">
                                        <i class="fa-solid fa-file-signature mr-2"></i>
                                        Alquilar
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="flex flex-col justify-center pt-4">
                            <h2 class="text-xl font-bold">Medios de pago disponibles</h2>
                            <div class="grid grid-cols-2 justify-center items-center gap-x-3">
                                <div class="grayscale-100 hover:grayscale-0 transition-all duration-300 ease-in-out">
                                    <img src="{{ asset('mercadoPago.png') }}" alt="mercadoPago"/>
                                </div>
                                <div class="grayscale-100 hover:grayscale-0 transition-all duration-300 ease-in-out relative px-6">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/1/12/Binance_logo.svg/1280px-Binance_logo.svg.png" alt="binance"/>
                                    <span class="text-red-700 absolute top-0 right-3">*</span>
                                </div>
                                <p class="text-gray-400 col-span-2 text-center"><span class="text-red-700">*</span>el pago en criptomonedas sólo está disponible presencialmente</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>

