
<x-layouts.app>
    <x-slot:title>
        Login
    </x-slot:title>

    //Puede testearse inputs desde esta blade
    <form action="/mercado" method="get">
        <input type="text" name="title" placeholder="Título" required>
        <input type="number" name="unit_price" placeholder="Precio" required>
        <button type="submit">Pagar</button>
    </form>
</x-layouts.app>
