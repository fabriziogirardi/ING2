@props(['label', 'name', 'id', 'components'])

<div>
    <span class="block mb-2 text-sm font-medium text-gray-900"></span>
    <label for="government_id_type_id" class="font-bold">
        {{ $label }}
    </label>
    <div class="mt-1">
        <select name='{{ $name }}' id='{{ $id }}'
                class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required>
            <option value="">{{ __('auth.select_a_option') }}</option>
            @foreach($components as $type)
                <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
        </select>
    </div>
</div>
