<x-layouts.app>
    <x-slot:title>
        Editar Respuesta
    </x-slot:title>

    <div class="bg-gray-50 h-screen flex flex-col items-center justify-center">
        <livewire:customer.forum.edit-reply-manage :reply="$reply"/>
    </div>
</x-layouts.app>
