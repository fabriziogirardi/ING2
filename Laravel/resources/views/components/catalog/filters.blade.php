@php
    $today = \Carbon\Carbon::today()->format('m/d/Y');
@endphp
@props(['start_date', 'end_date'])
<div class="bg-gray-50 border-b border-gray-200">
    <div class="mx-auto max-w-3xl px-4 text-center sm:px-6 lg:max-w-7xl lg:px-8">
        <div class="py-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ __('catalog/forms.catalog_title') }}</h1>
            <h2 class="mt-2">{{ __('catalog/forms.catalog_subtitle') }}</h2>
        </div>

        <section aria-labelledby="filter-heading" class="border-t border-gray-200">
            <h2 id="filter-heading" class="sr-only">Product filters</h2>
            <form action="" method="GET">
                <div class="flex flex-row justify-between items-center py-4">
                    <h1 class="font-bold text-lg">{{ __('catalog/forms.rental_date') }}</h1>
                    <div id="date-range-picker" date-rangepicker class="flex items-center">
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                </svg>
                            </div>
                            <input id="datepicker-range-start" datepicker datepicker-min-date="{{ $today }}" name="start" type="text"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                   placeholder="{{ __('catalog/forms.select_date_start') }}"
                                   value="{{ $start_date }}"
                            >
                        </div>
                        <span class="mx-4 text-gray-500">{{ __('catalog/forms.to') }}</span>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                                </svg>
                            </div>
                            <input id="datepicker-range-end" datepicker datepicker-min-date="{{ $today }}" name="end" type="text"
                                   class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full ps-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                   placeholder="{{ __('catalog/forms.select_date_end') }}"
                                   value="{{ $end_date }}"
                            >
                        </div>
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-400 border border-transparent rounded-md shadow-sm hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fa-solid fa-magnifying-glass mr-2"></i>
                        {{ __('catalog/forms.search') }}
                    </button>
                </div>
            </form>
        </section>
    </div>
</div>
