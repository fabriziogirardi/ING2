<x-layouts.app>
    <x-slot:title>
        {{ __('employee/payment.payment_view_title')  }}
    </x-slot:title>

    <section class="h-screen bg-white md:py-16 flex items-center mb-16">
        <div class="max-w-screen-xl px-4 mx-auto 2xl:px-0">
            <div class="lg:grid lg:grid-cols-2 lg:gap-8 xl:gap-16 items-center justify-center">

                {{-- Columna de texto --}}
                <div class="mt-6 sm:mt-8 lg:mt-0 bg-gray-50 px-8 py-12 rounded-xl shadow-md">
                    <h2 class="text-2xl font-bold mb-4 text-gray-800">Detalles de pago</h2>

                    <p class="text-lg text-gray-700 mb-2">
                        Maquinaria: <span class="font-semibold">{{ $product->name }}</span>
                    </p>
                    <p class="text-lg text-gray-700 mb-2">
                        Precio por día: <span class="font-semibold">${{ number_format($product->price, 2, ',', '.') }}</span>
                    </p>
                    <p class="text-lg text-gray-700 mb-2">
                        Cantidad de días: <span class="font-semibold">{{ $days }}</span>
                    </p>
                    <p class="text-xl text-blue-600 font-bold mt-4">
                        Total a pagar: ${{ number_format($total, 2, ',', '.') }}
                    </p>

                    <hr class="my-4 border-gray-300 opacity-60">
                    <p class="text-base text-gray-400 mb-2">
                        Por favor escanee el QR en su cuenta de Binance para abonar el total.
                    </p>
                    <form action="{{ route('employee.payment_confirm') }}" method="GET" class="mt-6">
                        <input type="hidden" name="branch_product_id" value="{{ $branchProductId }}">
                        <input type="hidden" name="start_date" value="{{ request()->start_date }}">
                        <input type="hidden" name="end_date" value="{{ request()->end_date }}">
                        <input type="hidden" name="total_amount" value="{{ $total }}">

                        <div class="mb-4">
                            <label for="customer_email" class="block text-gray-700 font-semibold mb-2">Correo del cliente</label>
                            <input type="email" name="customer_email" id="customer_email" required
                                   class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
                                   placeholder="cliente@ejemplo.com">
                            @if ($errors->any())
                                <div class="mb-4">
                                    <ul class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>

                        <div class="flex justify-center">
                            <button type="submit" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded-lg transition duration-300">
                                Confirmar pago
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Columna con QR --}}
                <div class="flex justify-center items-center">
                    <img src="{{ asset('img/binanceQR.jpg') }}" alt="QR de Binance" class="w-96 h-96 object-contain shadow-lg rounded-lg border">
                </div>

            </div>
        </div>
    </section>
</x-layouts.app>
