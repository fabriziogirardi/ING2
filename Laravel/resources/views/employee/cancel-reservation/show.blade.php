<x-layouts.app x-data>
    <x-slot:title>
        Rembolso de Reserva
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
            <form class="mt-4 space-y-6 sm:mt-6" action="{{ route('employee.cancel-reservation.store') }}" method="POST" @submit="submit = true">
                @csrf
                <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
                <input type="hidden" name="refund_amount" value="{{ $refund  }}">
                <div class="flex items-center">
                    <x-forms.submit text="Confirmar Devolucion" submit="true" full-width="true" />
                </div>
            </form>
        </div>
    </div>

</x-layouts.app>
