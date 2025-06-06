<x-layouts.app x-data>
    <x-slot:title>
        {{ __('product/forms.brand.brands') }}
    </x-slot:title>

    <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <!-- Start coding here -->
            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                <div class="flex flex-col md:flex-row items-center justify-left space-y-3 md:space-y-0 md:space-x-4 p-4">
                    <div class="w-full md:w-1/2">
                        <x-elements.link-button text="{{ __('product/forms.brand.add_brand') }}" href="{{ route('manager.brand.create') }}" icon-left="fa-solid fa-plus"></x-elements.link-button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">{{ __('product/forms.brand.brand_name') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('product/forms.brand.models') }}</th>
                            <th scope="col" class="px-4 py-3">
                                <span class="sr-only">{{ __('product/forms.brand.actions') }}</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($brands as $brand)
                            <tr class="border-b dark:border-gray-700">
                                <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    @if($brand->trashed())
                                        <span class="line-through">{{ $brand->name }}</span>
                                    @else
                                        <a href="{{ route('manager.brand.show', $brand->id) }}" class="text-blue-600 hover:underline">
                                            {{ $brand->name }}
                                        </a>
                                    @endif
                                </th>
                                <td class="px-4 py-3">{{ $brand->models()->count() }}</td>
                                <td class="px-4 py-3 flex items-center justify-end space-x-2">
                                    <x-elements.link-button
                                        href="{{ route('manager.brand.edit', $brand->id) }}"
                                        icon-left="fa-solid fa-pen-to-square"
                                        type="alert"
                                        :disabled="$brand->trashed()"
                                    />
                                    <x-elements.link-button
                                        icon-left="fa-solid fa-trash"
                                        type="danger"
                                        href="#"
                                        data-modal-target="popup-modal-{{ $brand->id }}"
                                        data-modal-toggle="popup-modal-{{ $brand->id }}"
                                        onclick="event.preventDefault();"
                                        :disabled="$brand->trashed()"
                                    />
                                    <form method="POST" action="{{ route('manager.brand.restore', $brand->id) }}">
                                        @csrf
                                        <x-elements.link-button href="{{ route('manager.brand.restore', $brand->id) }}" icon-left="fa-solid fa-repeat" :disabled="!$brand->trashed()"/>
                                    </form>
                                    <x-forms.confirmation-delete
                                        :message="__('product/forms.brand.delete_confirm', ['item' => $brand->name])"
                                        :route="route('manager.brand.destroy', $brand->id)"
                                        :id="'popup-modal-' . $brand->id"
                                    />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-center">{{ __('product/forms.brand.no_brands_found') }}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <nav class="flex flex-col md:flex-row flex justify-end items-start md:items-center space-y-3 md:space-y-0 p-4" aria-label="Table navigation">
                    {{ $brands->links() }}
                </nav>
            </div>
        </div>
    </section>
</x-layouts.app>
