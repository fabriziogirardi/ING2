<x-layouts.app x-data>
    <x-slot:title>
        Login
    </x-slot:title>

    <h1>{{ $message }}</h1>
    <p>Producto: {{ $product->name }}</p>
    <p>Monto a devolver: ${{ number_format($refund, 2) }}</p>

</x-layouts.app>
