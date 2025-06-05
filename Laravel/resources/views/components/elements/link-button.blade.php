<a href="{{ !$disabled ? $href : "#" }}" {{ $attributes->merge(['class' => $$type . ($disabled ? ' opacity-25 cursor-not-allowed' : '') . ($fullWidth ? ' w-full' : '')]) }} target="{{ $target }}">
    <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white rounded-md group-hover:bg-transparent w-full">
        @if($iconLeft)
            <span class="inline-block mr-2">
                <i class="{{ $iconLeft }}"></i>
            </span>
        @endif
        @if($text)
            {{ $text }}
        @endif
        @if($iconRight)
            <span class="inline-block ml-2">
                <i class="{{ $iconRight }}"></i>
            </span>
        @endif
    </span>
</a>
