<x-layouts.app x-data>
    <x-slot:title>
        Login
    </x-slot:title>

    <div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900">
        <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                Verifica con el cliente los datos de la politica de cancelacion antes de cancelar la reserva
            </h5>
            <div class="mb-4">
                <p><strong>{{ $message }}</strong></p>
                <p><strong>Producto: </strong> {{ $product->name }} </p>
                <p><strong>Monto a devolver:</strong> ${{ number_format($refund, 2) }}</p>
            </div>


        </div>
    </div>

</x-layouts.app>
