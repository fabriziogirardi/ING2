<x-layouts.app>
    <x-slot:title>
        Confirmación de Reserva
    </x-slot:title>

    <section class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
        <div class="bg-white p-8 rounded-lg shadow-lg max-w-md w-full text-center">
            <h1 class="text-3xl font-bold mb-4 text-green-600">¡Reserva Confirmada!</h1>

            <p class="text-lg mb-6">
                Tu código de reserva es:
            </p>

            <div class="text-4xl font-mono font-bold mb-8 text-gray-800 select-all">
                {{ $code }}
            </div>

            {{-- Mostrar detalles del pago solo si se pasan las variables --}}
            @if(isset($baseTotal) && isset($finalTotal))
                <div class="text-left bg-gray-50 p-4 rounded-lg mb-6">
                    <h3 class="font-semibold text-gray-800 mb-2">Detalles del pago:</h3>
                    <p class="text-sm text-gray-600 mb-1">
                        Subtotal: ${{ number_format($baseTotal, 2, ',', '.') }}
                    </p>
                    @if(isset($hasPenalization) && $hasPenalization)
                        <p class="text-sm text-red-600 mb-1">
                            Recargo por penalización (10%): ${{ number_format($finalTotal - $baseTotal, 2, ',', '.') }}
                        </p>
                    @endif
                    <hr class="my-2">
                    <p class="text-base font-semibold text-gray-800">
                        Total pagado: ${{ number_format($finalTotal, 2, ',', '.') }}
                    </p>
                </div>

                @if(isset($hasPenalization) && $hasPenalization)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3 mb-6">
                        <p class="text-sm text-yellow-800">
                            <strong>Nota:</strong> Se aplicó un recargo del 10% debido a devoluciones tardías.
                        </p>
                    </div>
                @endif
            @endif

            <a href="{{ route('catalog.index') }}"
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded transition">
                Volver al catálogo
            </a>
        </div>
    </section>
</x-layouts.app>
