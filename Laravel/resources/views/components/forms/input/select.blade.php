<div {{ $attributes->merge(['class' => 'mb-2']) }}>
    <label for="{{ $name }}" class="block mb-2 text-sm font-medium text-gray-900 w-100">{{ $label }}</label>
    <select id="{{ $name }}" name="{{ $name }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
        <option selected disabled>{{ $placeholder }}</option>
        @foreach($options as $option)
            <option value="{{ $option['id'] }}" {{ $old === $option['id'] ? 'selected' : '' }}>{{ $option['name'] }}</option>
        @endforeach
    </select>
</div>
