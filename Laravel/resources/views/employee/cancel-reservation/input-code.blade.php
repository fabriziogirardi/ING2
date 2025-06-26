<x-layouts.app x-data>
    <x-slot:title>
        Login
    </x-slot:title>

    <form action="{{ route('employee.cancel-reservation.show') }}" method="post">
        @csrf
        <label for="refund_amount">Codigo</label>
        <input
            type="text"
            name="code"
            id="code"
            required
        >
        <button type="submit">Enviar</button>
    </form>


</x-layouts.app>
