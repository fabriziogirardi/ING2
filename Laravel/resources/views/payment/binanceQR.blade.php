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
                        Cliente: <span class="font-semibold">{{ $customer->person->full_name }}</span>
                    </p>
                    <p class="text-lg text-gray-700 mb-2">
                        Maquinaria: <span class="font-semibold">{{ $product->name }}</span>
                    </p>
                    <p class="text-lg text-gray-700 mb-2">
                        Precio por día: <span class="font-semibold">${{ number_format($product->price, 2, ',', '.') }}</span>
                    </p>
                    <p class="text-lg text-gray-700 mb-2">
                        Cantidad de días: <span class="font-semibold">{{ $days }}</span>
                    </p>
                    @if(isset($discountAmount) && $discountAmount > 0)
                        <p class="text-lg text-green-600 mb-2">
                            Descuento aplicado: <span class="font-semibold">- ${{ number_format($discountAmount, 2, ',', '.') }}</span>
                        </p>
                    @endif

                    {{-- Mostrar desglose del precio --}}
                    <div class="border-t pt-4 mt-4">
                        <p class="text-lg text-gray-700 mb-2">
                            Subtotal: <span class="font-semibold">${{ number_format($baseTotal, 2, ',', '.') }}</span>
                        </p>

                        @if($hasPenalization)
                            <p class="text-lg text-red-600 mb-2">
                                Recargo por penalización (10%): <span class="font-semibold">${{ number_format($finalTotal - $baseTotal, 2, ',', '.') }}</span>
                            </p>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-4">
                                <p class="text-sm text-yellow-800">
                                    <strong>Nota:</strong> Se aplicó un recargo del 10% debido a devoluciones tardías.
                                </p>
                            </div>
                        @endif

                        <p class="text-xl text-blue-600 font-bold mt-4">
                            Total a pagar: ${{ number_format($finalTotal, 2, ',', '.') }}
                        </p>
                    </div>

                    <hr class="my-4 border-gray-300 opacity-60">
                    <p class="text-base text-gray-400 mb-2">
                        Por favor escanee el QR en su cuenta de Binance para abonar el total.
                    </p>
                    <form action="{{ route('employee.payment_confirm') }}" method="GET" class="mt-6">
                        <input type="hidden" name="branch_product_id" value="{{ $branchProductId }}">
                        <input type="hidden" name="start_date" value="{{ request()->start_date }}">
                        <input type="hidden" name="end_date" value="{{ request()->end_date }}">
                        <input type="hidden" name="customer_email" value="{{ $customer->person->email }}">

{{--                        <div class="mb-4">--}}
{{--                            <label for="customer_email_display" class="block text-gray-700 font-semibold mb-2">Correo del cliente</label>--}}
{{--                            <input type="email" name="customer_email_display" id="customer_email_display"--}}
{{--                                   value="{{ $customer->person->email }}" readonly--}}
{{--                                   class="w-full px-4 py-2 border rounded-lg bg-gray-100 focus:outline-none">--}}
{{--                        </div>--}}

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
