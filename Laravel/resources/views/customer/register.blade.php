<x-layouts.app>
    <x-slot:title>
        Register
    </x-slot:title>

    <section class="bg-gray-50 h-full">
        <div class="grid max-w-screen-xl px-4 py-8 mx-auto lg:gap-20 lg:py-16 lg:grid-cols-12">
            <div class="w-full place-self-center lg:col-span-6">
                <div class="p-6 mx-auto bg-white rounded-lg shadow sm:max-w-xl sm:p-8">
                    <h1 class="mb-2 text-2xl font-bold leading-tight tracking-tight text-gray-900">
                        Registrar a un nuevo cliente!
                    </h1>
                    <form class="mt-4 space-y-6 sm:mt-6" action="{{ route('customer.register') }}" method="POST">
                        @csrf
                        <div class="grid gap-6 sm:grid-cols-2">

                            <div>
                                <span class="block mb-2 text-sm font-medium text-gray-900"></span>
                                <label for="government_id_type_id" class="font-bold">
                                    {{ __('validation.attributes.government_id_type') }}
                                </label>
                                <div class="mt-1">
                                    <select name="government_id_type_id" id="government_id_type_id"
                                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5" required>
                                        <option value="">{{ __('validation.select_a_option') }}</option>
                                        @foreach($idTypes as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <x-forms.input-text label="{{ __('validation.attributes.government_id_number') }}" name="government_id_number"/>

                            <x-forms.input-text label="{{ __('validation.attributes.first_name') }}" name="first_name"/>

                            <x-forms.input-text label="{{ __('validation.attributes.last_name') }}" name="last_name"/>

                            <x-forms.input-email label="{{ __('validation.attributes.email') }}" name="email"/>

                            <x-forms.input-date label="{{ __('validation.attributes.birthdate') }}" name="birth_date"/>

                        </div>
                        <button type="submit" class="w-full text-gray bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Sign in to your account</button>
                    </form>
                </div>
            </div>
            <div class="mr-auto place-self-center lg:col-span-6">
                <img class="hidden mx-auto lg:flex"
                     src="{{ asset('img/login-form-image.png') }}"
                     alt="login-form-image">
            </div>
        </div>
    </section>
</x-layouts.app>
