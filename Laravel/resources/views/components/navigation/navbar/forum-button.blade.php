<x-elements.link-button :icon-left="'fa-solid fa-comments'" {{ $attributes->merge(['class' => 'text-center'])}} href="{{ route('forum.index') }}">
    <x-slot:text>Foro</x-slot:text>
</x-elements.link-button>
