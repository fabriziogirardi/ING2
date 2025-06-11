<x-layouts.app x-data>
    <x-slot:title>
        {{ __('product/forms.model.edit_title') }}
    </x-slot:title>

    <section class="bg-gray-50 h-full">
        <div class="grid max-w-screen-xl px-4 py-8 mx-auto lg:gap-20 lg:py-16 lg:grid-cols-12">
            <div class="w-full place-self-center lg:col-span-6">
                <div class="p-6 mx-auto bg-white rounded-lg shadow sm:max-w-xl sm:p-8">
                    <h1 class="mb-2 text-2xl font-bold leading-tight tracking-tight text-gray-900">
                        {{ __('product/forms.model.edit_title') }}
                    </h1>
                    <form class="mt-4 space-y-6 sm:mt-6" action="{{ route('manager.model.update', $model->id) }}" method="POST" @submit="submit = true">
                        @csrf
                        @method('PATCH')
                        <div class="grid gap-6 sm:grid-cols-2">
                            <x-forms.input.text
                                name="name"
                                id="name"
                                label="{{ __('product/forms.model.name') }}"
                                placeholder="{{ __('product/forms.model.placeholder') }}"
                                value="{{ old('name', $model->name) }}"
                            />
                            <x-forms.input.select
                                name="product_brand_id"
                                label="{{ __('product/forms.model.brand') }}"
                                :options="$brands"
                                :old="old('product_brand_id', $model->product_brand_id)"
                                placeholder="{{ __('product/forms.model.select_brand') }}"
                            />
                        </div>
                        <x-forms.error-description message="{{ $errors->first('name') }}" class="sm:col-span-2" />
                        <x-forms.error-description message="{{ $errors->first('product_brand_id') }}" class="sm:col-span-2" />
                        <div class="flex items-center">
                            <x-forms.submit text="{{ __('product/forms.edit') }}" icon-left="fa-solid fa-pen-to-square" submit="true" full-width="true" type="alert" />
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</x-layouts.app>
