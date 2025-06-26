<x-layouts.app x-data>
    <x-slot:title>
        Login
    </x-slot:title>

    <h1>{{ $message }}</h1>
    <p>Producto: {{ $product->name }} (Precio: ${{ number_format($maxValue, 2) }})</p>

    <form action="{{ route('employee.cancel-reservation.partial') }}" method="post">
        @csrf
        <input type="hidden" name="maxValue" value="{{ $maxValue }}">
        <label for="refund_amount">Monto a devolver:</label>
        <input
            type="number"
            name="refund_amount"
            id="refund_amount"
            step="0.01"
            max="{{ $maxValue }}"
            required
        >
        <button type="submit">Enviar</button>
    </form>

</x-layouts.app>
