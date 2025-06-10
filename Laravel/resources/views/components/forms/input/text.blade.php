<div {{ $attributes->merge(['class' => 'mb-2']) }} x-data="{ error: {{ $errors->has($name) ? 'true' : 'false'}} }">
    <label for="{{ $name }}" class="block mb-2 text-sm font-medium text-gray-900">
        {{ $label }}
        @if($required)
            <span class="text-red-500">*</span>
        @elseif($optional)
            <span class="text-gray-400 italic">({{ __('forms.optional') }})</span>
        @endif
    </label>
    <div class="relative">
        @if($iconLeft)
            <i class="absolute inset-y-0 start-0 flex items-center ps-3.5 pointer-events-none {{ $icon }} text-gray-500" aria-hidden="true" />
        @endif
        <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}"
               class="text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full {{ $iconLeft ? "ps-10 " : "" }}p-2.5"
               placeholder="{{ $placeholder }}"
               {{ $required ? 'required' : '' }}
               :class="error ? 'bg-red-100 border border-red-300' : 'bg-gray-50 border border-gray-300'"
               @keydown="error = false;"
               value="{{ old($name, $value) }}"
               @if($disabled)
                   disabled
               @endif
               @if($pattern)
                   pattern="{{ $pattern }}"
               @endif
               @if($maxlength)
                   maxlength="{{ $maxlength }}"
               @endif
               @if($minlength)
                   minlength="{{ $minlength }}"
               @endif
               @if($min)
                   min="{{ $min }}"
               @endif
               @if($max)
                   max="{{ $max }}"
               @endif
               @if($step)
                   step="{{ $step }}"
               @endif
        >
    </div>
    @if($error)
        <div :class="error ? 'opacity-100' : 'opacity-0'">
            <x-forms.error-description message="{{ $error }}" />
        </div>
    @endif
</div>
