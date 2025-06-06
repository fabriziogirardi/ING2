<x-layouts.app x-data>
    <x-slot:title>
        {{ __('product/forms.brands') }}
    </x-slot:title>

    <section class="bg-gray-50 dark:bg-gray-900 p-3 sm:p-5">
        <div class="mx-auto max-w-screen-xl px-4 lg:px-12">
            <!-- Start coding here -->
            <div class="bg-white dark:bg-gray-800 relative shadow-md sm:rounded-lg overflow-hidden">
                <div class="flex flex-col md:flex-row items-center justify-left space-y-3 md:space-y-0 md:space-x-4 p-4">
                    <div class="w-full md:w-1/2">
                        <x-elements.link-button text="{{ __('product/forms.add_brand') }}" href="{{ route('manager.brand.create') }}" icon-left="fa-solid fa-plus"></x-elements.link-button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-4 py-3">{{ __('product/forms.brand_name') }}</th>
                            <th scope="col" class="px-4 py-3">{{ __('product/forms.models') }}</th>
                            <th scope="col" class="px-4 py-3">
                                <span class="sr-only">{{ __('product/forms.actions') }}</span>
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($brands as $brand)
                            <tr class="border-b dark:border-gray-700">
                                <th scope="row" class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    <a href="{{ route('manager.brand.show', $brand->id) }}" class="text-blue-600 hover:underline">
                                        {{ $brand->name }}
                                    </a>
                                </th>
                                <td class="px-4 py-3">{{ $brand->models()->count() }}</td>
                                <td class="px-4 py-3 flex items-center justify-end">
                                    <x-elements.link-button  text="{{ __('product/forms.edit') }}" href="{{ route('manager.brand.edit', $brand->id) }}" icon-left="fa-solid fa-pen-to-square" type="alert">
                                    </x-elements.link-button>

                                    <x-elements.link-button
                                        text="{{ __('product/forms.delete') }}"
                                        icon-left="fa-solid fa-trash"
                                        type="danger"
                                        href="#"
                                        data-modal-target="popup-modal"
                                        data-modal-toggle="popup-modal"
                                        onclick="event.preventDefault();"
                                    >
                                    </x-elements.link-button>

                                    <div id="popup-modal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                        <div class="relative p-4 w-full max-w-md max-h-full">
                                            <div class="relative bg-white rounded-lg shadow-sm dark:bg-gray-700">
                                                <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="popup-modal">
                                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                    </svg>
                                                    <span class="sr-only">{{ __('product/forms.close_modal') }}</span>
                                                </button>
                                                <div class="p-4 md:p-5 text-center">
                                                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                    </svg>
                                                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">{{ __('product/forms.delete_confirm') }}</h3>
                                                    <form method="POST" action="{{ route('manager.brand.destroy', $brand->id) }}" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button
                                                            data-modal-hide="popup-modal"
                                                            type="submit"
                                                            class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center"
                                                        >
                                                            {{ __('product/forms.yes_im_sure') }}
                                                        </button>
                                                    </form>
                                                    <button data-modal-hide="popup-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">{{ __('product/forms.no_cancel') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-center">{{ __('product/forms.no_brands_found') }}</td>
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
