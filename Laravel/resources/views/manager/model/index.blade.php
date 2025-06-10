<x-layouts.app x-data>
    <x-slot:title>
        {{ __('product/forms.model.models') }}
    </x-slot:title>

    <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                <div class="flex flex-col md:flex-row items-center justify-left space-y-3 md:space-y-0 md:space-x-4 p-4">
                    <div class="w-full md:w-1/2">
                        <x-elements.link-button
                            text="{{ __('product/forms.model.add_model') }}"
                            href="{{ route('manager.model.create') }}"
                            icon-left="fa-solid fa-plus">
                        </x-elements.link-button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">{{ __('product/forms.model.model_name') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('product/forms.model.brand_name') }}</th>
                            <th scope="col" class="px-4 py-3">
                                <span class="sr-only">{{ __('product/forms.model.actions') }}</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($models as $model)
                            <tr class="border-b dark:border-gray-700">
                                <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <a href="{{ route('manager.model.show', $model->id) }}" class="text-blue-600 hover:underline">
                                        {{ $model->name }}
                                    </a>
                                </th>
                                <td class="px-4 py-3">
                                    {{ $model->brand->name ?? "borrado" }}
                                </td>
                                <td class="px-4 py-3 flex items-center justify-end">
                                    <x-elements.link-button
                                        text="{{ __('product/forms.edit') }}"
                                        href="{{ route('manager.model.edit', $model->id) }}"
                                        icon-left="fa-solid fa-pen-to-square"
                                        type="alert">
                                    </x-elements.link-button>

                                    <x-elements.link-button
                                        text="{{ __('product/forms.delete') }}"
                                        icon-left="fa-solid fa-trash"
                                        type="danger"
                                        href="#"
                                        data-modal-target="popup-modal-{{ $model->id }}"
                                        data-modal-toggle="popup-modal-{{ $model->id }}"
                                        onclick="event.preventDefault();"
                                    />

                                    <x-forms.confirmation-delete
                                        :message="__('product/forms.model.delete_confirm', ['item' => $model->name])"
                                        :route="route('manager.model.destroy', $model->id)"
                                        :id="'popup-modal-' . $model->id"
                                    />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-center">{{ __('product/forms.model.no_models_found') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            {{ $models->links() }}
        </div>
    </section>
</x-layouts.app>
