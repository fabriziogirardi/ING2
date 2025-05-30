<x-layouts.app>
    <x-slot:title>
        Register
    </x-slot:title>

    <section class="bg-gray-50 h-full">
        <div class="grid max-w-screen-xl px-4 py-8 mx-auto lg:gap-20 lg:py-16 lg:grid-cols-12">
            <div class="w-full place-self-center lg:col-span-6">
                <div class="p-6 mx-auto bg-white rounded-lg shadow sm:max-w-xl sm:p-8">
                    <a href="#"
                       class="inline-flex items-center mb-4 text-xl font-semibold text-gray-900">
                        <img class="w-8 h-8 mr-2" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/logo.svg"
                             alt="logo">
                        Flowbite
                    </a>
                    <h1 class="mb-2 text-2xl font-bold leading-tight tracking-tight text-gray-900">
                        Welcome!
                    </h1>
                    <form class="mt-4 space-y-6 sm:mt-6" action="{{ route('customer.register') }}" method="POST">
                        @csrf
                        <div class="grid gap-6 sm:grid-cols-2">
                            <div>
                                <label for="government_id_number"
                                       class="block mb-2 text-sm font-medium text-gray-900">{{ __('validation.attributes.government_id_number') }}</label>
                                <input type="text" name="government_id_number" id="government_id_number" placeholder="12345678"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                       required="">
                            </div>

                            <div>
                                <label for="email"
                                       class="block mb-2 text-sm font-medium text-gray-900">{{ __('validation.attributes.email') }}</label>
                                <input type="email" name="email" id="email"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                       placeholder="example@email.com" required="">
                            </div>

                            <div>
                                <label for="first_name"
                                       class="block mb-2 text-sm font-medium text-gray-900">{{ __('validation.attributes.first_name') }}</label>
                                <input type="text" name="first_name" id="first_name" placeholder="Juan"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                       required="">
                            </div>

                            <div>
                                <label for="last_name"
                                       class="block mb-2 text-sm font-medium text-gray-900">{{ __('validation.attributes.last_name') }}</label>
                                <input type="text" name="last_name" id="last_name" placeholder="Perez"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                       required="">
                            </div>

                            <div>
                                <label for="birth_date"
                                       class="block mb-2 text-sm font-medium text-gray-900">{{ __('validation.attributes.birthdate') }}</label>
                                <input type="date" name="birth_date" id="birth_date" placeholder=""
                                       class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                       required="">
                            </div>
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
