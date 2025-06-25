<x-layouts.app>
    <x-slot:title>
        Editar Discusión
    </x-slot:title>

    <div class="bg-gray-50 h-screen flex flex-col items-center justify-center">
        <livewire:customer.forum.edit-discussion-manage :discussion="$discussion"/>
    </div>
</x-layouts.app>
