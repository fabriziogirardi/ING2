<x-layouts.app>
    <x-slot:title>
        {{ __('reservation/forms.title_sucess_slot') }}
    </x-slot:title>

    <div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-900">
        <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
            <h5 class="mb-2 text-2xl font-bold tracking-tight text-green-700 dark:text-green">
                {{ __('reservation/forms.title_sucess') }}
            </h5>
            <p class="font-normal text-gray-700 dark:text-gray-400">
                {{ __('reservation/forms.text_sucess') }}
            </p>
            <div class="mt-4">
                <x-elements.link-button
                    :text="__('reservation/forms.back_to_page')"
                    href="/"
                    icon-left="fa-solid fa-rotate-left">
                </x-elements.link-button>
            </div>
        </div>
    </div>
</x-layouts.app>
