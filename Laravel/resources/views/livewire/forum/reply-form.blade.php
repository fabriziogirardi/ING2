<div class="container mx-auto bg-gray-100 p-4 rounded-lg shadow-md {{ Auth::getCurrentGuard() === 'manager' ? 'hidden' : '' }}">
    <form wire:submit.prevent="submit">
        <h1 class="text-2xl font-bold">{{ request()->routeIs('forum.discussions.show') ? 'Responder Discusión' : 'Editar Respuesta' }}</h1>
        <div class="grid grid-cols-1 gap-4">
            @livewire('notifications')
            {{ $this->form }}
        </div>
        <div class="ml-3">
            <button type="submit" class="relative inline-flex items-center justify-center p-0.5 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-cyan-500 to-blue-500 group-hover:from-cyan-500 group-hover:to-blue-500 hover:text-white">
                <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white rounded-md group-hover:bg-transparent w-full">
                    {{ request()->routeIs('forum.discussions.show') ? 'Responder' : 'Guardar Cambios' }}
                </span>
            </button>
            @if(request()->routeIs('forum.reply.edit'))
                <x-elements.link-button href="{{ route('forum.discussions.show', ['discussion' => $reply->forum_discussion_id]) }}" class="ml-2" type="danger">
                    <x-slot:text>Cancelar</x-slot:text>
                </x-elements.link-button>
            @endif
        </div>
    </form>
</div>
