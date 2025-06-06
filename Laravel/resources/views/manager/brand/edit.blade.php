<x-layouts.app x-data>
    <x-slot:title>
        {{ __('product/forms.edit_title') }}
    </x-slot:title>

    <section class="bg-gray-50 h-full">
        <div class="grid max-w-screen-xl px-4 py-8 mx-auto lg:gap-20 lg:py-16 lg:grid-cols-12">
            <div class="w-full place-self-center lg:col-span-6">
                <div class="p-6 mx-auto bg-white rounded-lg shadow sm:max-w-xl sm:p-8">
                    <h1 class="mb-2 text-2xl font-bold leading-tight tracking-tight text-gray-900">
                        {{ __('product/forms.edit') }}
                    </h1>
                    <form class="mt-4 space-y-6 sm:mt-6" action="{{ route('manager.brand.update', $brand->id) }}" method="POST" @submit="submit = true">
                        @csrf
                        @method('PATCH')
                        <div class="grid gap-6 sm:grid-cols-2">
                            <x-forms.input.text
                                name="name"
                                id="name"
                                label="{{ __('product/forms.new_name') }}"
                                placeholder="{{ __('product/forms.placeholder') }}"
                                value="{{ old('name', $brand->name) }}"
                            />
                        </div>
                        <x-forms.error-description message="{{ $errors->has('name') ? $errors->first('name') : '' }}" class="sm:col-span-2" />
                        <div class="flex items-center">
                            <x-forms.submit text="{{ __('product/forms.edit') }}" icon-left="fa-solid fa-pen-to-square" submit="true" full-width="true" type="alert" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
