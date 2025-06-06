<x-layouts.app x-data>
    <x-slot:title>
        {{ __('product/forms.brand_name') }}
    </x-slot:title>

    <div class="p-6 pb-20 bg-white">
        <h5 class="mb-2 text-2xl font-bold text-gray-900 dark:text-white">
            {{ $brand->name }}
        </h5>
        <p class="font-normal text-gray-700 dark:text-gray-400 mb-2">
            {{ __('product/forms.models') }}:
        </p>
        <ul class="list-disc pl-6">
            @forelse ($brand->models as $model)
                <li class="text-gray-700 dark:text-gray-300">{{ $model->name }}</li>
            @empty
                <li class="text-gray-500 dark:text-gray-400">{{ __('product/forms.no_models_found') }}</li>
            @endforelse
        </ul>
        <div>
            <x-elements.link-button
                text="{{ __('product/forms.back_to_index') }}"
                href="{{ route('manager.brand.index') }}"
                icon-left="fa-solid fa-rotate-left">
            </x-elements.link-button>
        </div>
    </div>
</x-layouts.app>
