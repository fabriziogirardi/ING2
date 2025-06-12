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
                        <div class="hidden duration-700 ease-in-out" data-carousel-item>
                            <img src="{{ asset('bordeadora.jpg') }}" class="absolute block w-full h-full object-cover -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
                        </div>
                        <!-- Item 2 -->
                        <div class="hidden duration-700 ease-in-out" data-carousel-item="active">
                            <img src="{{ asset('img.png') }}" class="absolute block w-full h-full object-cover -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
                        </div>
                        <!-- Item 3 -->
                        <div class="hidden duration-700 ease-in-out" data-carousel-item="active">
                            <img src="{{ asset('img_1.png') }}" class="absolute block w-full h-full object-cover -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2" alt="...">
                        </div>
                    </div>
                    <!-- Slider controls -->
                    <button type="button" class="absolute top-0 -start-10 bg-white z-30 flex items-center justify-center h-full px-4 group" data-carousel-prev>
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full cursor-pointer group-hover:bg-blue-400/50 active:ring-4 active:ring-yellow-200 transition-colors duration-300 ease-in-out">
                            <i class="fa-solid fa-chevron-left"></i>
                            <span class="sr-only">Previous</span>
                        </span>
                    </button>
                    <button type="button" class="absolute top-0 -end-10 bg-white z-30 flex items-center justify-center h-full px-4 group" data-carousel-next>
                        <span class="inline-flex items-center justify-center w-10 h-10 rounded-full group-hover:bg-blue-400/50 cursor-pointer active:ring-4 active:ring-yellow-200 transition-colors duration-300 ease-in-out">
                            <i class="fa-solid fa-chevron-right"></i>
                            <span class="sr-only">Next</span>
                        </span>
                    </button>
                </div>

                <div class="mt-6 sm:mt-8 lg:mt-0 h-screen bg-gray-50 py-32 px-8">
                    <div class="flex flex-row flex-wrap pb-6 justify-center">
                        @foreach($product->categories as $category)
                            <a href="#" class="border-2 rounded-full px-2 py-0.5 border-blue-400 text-blue-400 font-bold">{{ $category->name }}</a>
                        @endforeach
                    </div>

                    <div class="flex items-center gap-x-3 pb-4 justify-center">
                        <p>{{ $product->model->brand->name }}</p>
                        <p>|</p>
                        <p>{{ $product->model->name }}</p>
                    </div>
                    <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl flex justify-center">
                        {{ $product->name }}
                    </h1>
                    <p class="mb-2 text-gray-500 flex justify-center">
                        {{ $product->description }}
                    </p>
                    <div class="border-t border-gray-300"></div>
                    <a type="button" href="#" class="inline-flex items-center justify-center w-full mt-4 text-lg font-bold text-white bg-blue-400 hover:bg-blue-500 focus:ring-4 focus:outline-none focus:ring-yellow-200 rounded-lg py-2.5 text-center mb-2 transition-colors duration-300 ease-in-out">
                        <i class="fa-solid fa-file-signature mr-2"></i>
                        Alquilar
                    </a>

                    <div class="flex flex-col justify-center pt-4">
                        <h2 class="text-xl font-bold">Medios de pago disponibles</h2>
                        <div class="grid grid-cols-2 justify-center items-center gap-x-3 h-8">
                            <div class="grayscale-100 hover:grayscale-0 transition-all duration-300 ease-in-out">
                                <img src="{{ asset('MP_RGB_HANDSHAKE_color-azul_hori-izq.png') }}" alt="mercadoPago"/>
                            </div>
                            <div class="grayscale-100 hover:grayscale-0 transition-all duration-300 ease-in-out relative">
                                <img src="https://download.logo.wine/logo/Binance/Binance-Logo.wine.png" alt="binance"/>
                                <span class="text-red-700 absolute top-20 right-5">*</span>
                            </div>
                            <p class="text-gray-400 col-span-2 text-center"><span class="text-red-700">*</span>el pago en criptomonedas sólo está disponible presencialmente</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>

