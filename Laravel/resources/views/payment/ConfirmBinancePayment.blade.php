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

            <a href="{{ route('catalog.index') }}"
               class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-6 rounded transition">
                Volver al catálogo
            </a>
        </div>
    </section>
</x-layouts.app>
