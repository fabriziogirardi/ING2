@props(['label', 'name', 'type'])

<div>
    @if ($label)
        <x-forms.label :$name :$label :$type/>
    @endif

    <div class="mt-1">
        {{ $slot }}

        <x-forms.error :error="$errors->first($name)" />
    </div>
</div>
