<div {{ $attributes->merge(['class' => 'mb-2']) }} x-data="{ error: {{ $errors->has($name) ? 'true' : 'false'}} }">
    <label for="{{ $name }}" class="block mb-2 text-sm font-medium text-gray-900 w-100">{{ $label }}</label>
    <select id="{{ $name }}" name="{{ $name }}"
            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
            :class="error ? 'bg-red-100 border border-red-300' : 'bg-gray-50 border border-gray-300'"
            @change="error = false;"
            @if($required) required @endif
    >
        <option value="" {{ !$old ? ' selected' : '' }}>{{ $placeholder }}</option>
        @foreach($options as $option)
            <option value="{{ $option['id'] }}" {{ $old === $option['id'] ? 'selected' : '' }}>{{ $option['name'] }}</option>
        @endforeach
    </select>
    @if($error)
        <div :class="error ? 'opacity-100' : 'opacity-0'">
            <x-forms.error-description message="{{ $error }}" />
        </div>
    @endif
</div>
