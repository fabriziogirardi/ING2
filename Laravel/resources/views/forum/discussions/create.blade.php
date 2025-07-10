<x-layouts.app>
    <x-slot:title>
        Crear Discusión
    </x-slot:title>

    <div class="bg-gray-50">
        <div class="grid grid-cols-2 items-center h-screen">
            <livewire:customer.forum.create-discussion-manage />
            <div class="h-164 flex items-center justify-center pr-24">
                <img src="{{ asset('img/forum_discussion_default.png') }}" alt="Imagen foro" class="max-h-full max-w-full object-contain">
            </div>
        </div>
    </div>
</x-layouts.app>
