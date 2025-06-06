<x-layouts.app x-data>
    <x-slot:title>
        {{ __('product/forms.model.model_name') }}
    </x-slot:title>

    <div class="p-6 pb-20 bg-white">
        <h5 class="mb-2 text-2xl font-bold text-gray-900 dark:text-white">
            {{ $model->name }}
        </h5>
        <p class="font-normal text-gray-700 dark:text-gray-400 mb-2">
            {{ __('product/forms.model.brand') }}:
            <span class="font-semibold">{{ $model->brand->name ?? '-' }}</span>
        </p>
        <div>
            <x-elements.link-button
                text="{{ __('product/forms.back_to_index') }}"
                href="{{ route('manager.model.index') }}"
                icon-left="fa-solid fa-rotate-left">
            </x-elements.link-button>
        </div>
    </div>
</x-layouts.app>
