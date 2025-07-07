<x-layouts.app>
    <x-slot:title>
        Foro - Inicio
    </x-slot:title>
    <div class="bg-gray-50 h-screen">
        <div>
            <div class="py-12">
                <h1 class="text-center text-4xl font-bold py-2">Bienvenido al Foro de Alkil.ar</h1>
                <p class="text-center text-xl mb-4">En este foro podrás encontrar temas de interés, hacer preguntas y compartir tus experiencias con la comunidad.</p>
            </div>

            @if(Auth::getCurrentGuard() === 'customer')
                <div class="w-full px-7 py-6 bg-blue-200">
                    <div class="w-3/4 mx-auto flex flex-row justify-between items-center ">
                        <h2 class="text-lg">Comienza a interactuar con otros usuarios!</h2>
                        <x-elements.link-button href="{{ route('forum.discussions.create') }}" type="alert">
                            <x-slot:text>Comenzar Discusión</x-slot:text>
                        </x-elements.link-button>
                    </div>
                </div>
            @endif

            <div class="flex flex-row justify-between items-center px-4 py-2 max-w-7xl mx-auto">
                <div class="flex flex-row justify-center my-4 space-x-4 max-w-4xl mx-auto">
                    @foreach(\App\Models\ForumSection::all() as $section)
                        <a href="{{ route('forum.index', ['section' => $section->id]) }}" class="p-2 border-2 border-yellow-400 rounded-full text-yellow-400 text-sm bg-white hover:bg-yellow-400 hover:text-white transition-colors duration-300">
                            {{ $section->name }}
                        </a>
                    @endforeach

                </div>
                <a href="{{ route('forum.index') }}" data-popover-target="clean-filter">
                    <i class="fa-solid fa-filter-circle-xmark text-lg"></i>
                </a>
                <div data-popover id="clean-filter" role="tooltip" class="absolute z-10 invisible inline-block w-64 text-sm text-gray-500 transition-opacity duration-300 bg-yellow-400 border border-gray-200 rounded-lg shadow-xs opacity-0 dark:text-gray-400 dark:border-gray-600 dark:bg-gray-800">
                    <div class="px-3 py-2 rounded-t-lg">
                        <h3 class="font-semibold text-white text-center">Eliminar Filtros</h3>
                    </div>
                    <div data-popper-arrow></div>
                </div>
            </div>

            <div class="mt-4">
                <h2 class="text-2xl font-semibold text-center">Discusiones Recientes</h2>
                <ul class="flex flex-col items-center">
                    @foreach($discussions as $discussion)
                        <li class="my-4 p-4 bg-white rounded-lg shadow-md w-3/4">
                            <a href="{{ route('forum.discussions.show', $discussion->id) }}" class="flex flex-row justify-between items-center">
                                <div class="w-3/4">
                                    <span class="bg-yellow-100 text-yellow-800 text-sm font-medium mb-2 px-2.5 py-0.5 rounded-lg dark:bg-yellow-900 dark:text-yellow-300">{{ $discussion->section->name }}</span>
                                    <div class="text-xl ml-1 mb-2">
                                        {{ $discussion->title }}
                                    </div>
                                    <div class="bg-gray-100 rounded-full px-3 py-1 text-sm text-gray-600 inline-flex items-center">
                                        <i class="fa-solid fa-comment mr-1"></i>
                                        {{ $discussion->replies()->count() }}
                                    </div>
                                </div>
                                <div class="flex flex-col">
                                    <div class="text-blue-400">
                                        {{ \App\Models\Customer::find($discussion->customer_id)->person->full_name }}
                                    </div>
                                    <div class="text-sm">
                                        {{ $discussion->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</x-layouts.app>
