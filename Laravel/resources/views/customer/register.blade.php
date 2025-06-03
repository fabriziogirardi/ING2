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
                    <form class="mt-4 space-y-6 sm:mt-6" action="{{ route('employee.register_customer') }}" method="POST">
                        @csrf
                        <div class="grid gap-6 sm:grid-cols-2">

                            <div>
                                <label for="government_id_type_id" class="block mb-2 text-sm font-medium text-gray-900">
                                    {{ __('validation.attributes.government_id_type') }}
                                </label>
                                <select id="government_id_type_id" name="government_id_type_id"
                                        class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" required>
                                    <option value="">{{ __('auth.select_a_option') }}</option>
                                    @foreach($idTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label for="government_id_number" class="block mb-2 text-sm font-medium text-gray-900">
                                    {{ __('validation.attributes.government_id_number') }}
                                </label>
                                <input type="text" id="government_id_number" name="government_id_number" value="{{ old('government_id_number') }}"
                                       class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                       placeholder="12345678" required />
                                @error('government_id_number')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="first_name" class="block mb-2 text-sm font-medium text-gray-900">
                                    {{ __('validation.attributes.first_name') }}
                                </label>
                                <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}"
                                       class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                       placeholder="John" required />
                                @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="last_name" class="block mb-2 text-sm font-medium text-gray-900">
                                    {{ __('validation.attributes.last_name') }}
                                </label>
                                <input type="text" id="last_name" name="last_name" value="{{ old('last_name') }}"
                                       class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                       placeholder="Doe" required />
                                @error('last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email" class="block mb-2 text-sm font-medium text-gray-900">
                                    {{ __('validation.attributes.email') }}
                                </label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                       class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                       placeholder="example@gmail.com" required />
                                @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="birth_date" class="block mb-2 text-sm font-medium text-gray-900">
                                    {{ __('validation.attributes.birthdate') }}
                                </label>
                                <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}"
                                       class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"
                                       required />
                                @error('birth_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="bg-white border border-gray-300 rounded-lg p-0.5 flex justify-center mt-6">
                                <button type="submit" class="w-full text-gray bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">{{ __('auth.register') }}</button>
                            </div>

                        </div>
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
