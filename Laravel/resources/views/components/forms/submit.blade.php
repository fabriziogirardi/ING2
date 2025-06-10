<button type="{{ $submit ? 'submit' : 'button' }}" {{ $attributes->merge(['class' => $$type . ($fullWidth ? ' w-full' : '')]) }}
    @if($name)
        name="{{ $name }}"
        @endif
        @if($value)
        value="{{ $value }}"
    @endif
    {{ $disabled ? 'disabled' : '' }}
    :class="submit ? 'opacity-25 cursor-not-allowed' : ''"
>
    <span class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white rounded-md group-hover:bg-transparent {{ $fullWidth ? 'w-full' : '' }}"

          :disabled="submit"
    >
        @if($iconLeft)
            <span :class="submit ? 'hidden' : 'inline-block mr-2'">
                <i class="{{ $iconLeft }}"></i>
            </span>
        @endif
        <span :class="submit ? 'inline-block mr-2' : 'hidden'">
            <i class="fa-solid fa-circle-notch fa-spin"></i>
        </span>
        {{ $text }}
    </span>
</button>
