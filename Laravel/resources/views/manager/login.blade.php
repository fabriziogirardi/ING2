<x-layouts.app>
    <x-slot:title>
        Login
    </x-slot:title>

    <section class="bg-gray-50 h-full" x-data="{ submit: false }">
        <div class="grid max-w-screen-xl px-4 py-8 mx-auto lg:gap-20 lg:py-16 lg:grid-cols-12">
            <div class="w-full place-self-center lg:col-span-6">
                <div class="p-6 mx-auto bg-white rounded-lg shadow sm:max-w-xl sm:p-8">
                    <h1 class="mb-2 text-2xl font-bold leading-tight tracking-tight text-gray-900">
                        {{ __('manager/auth.welcome_back') }}
                    </h1>
                    <p class="text-sm font-light text-gray-500">
                        {{ __('manager/auth.welcome_back_description') }}
                    </p>
                    <form class="mt-4 space-y-6 sm:mt-6" action="{{ route('manager.login') }}" method="POST" @submit="submit = true">
                        @csrf
                        <div class="grid gap-6 sm:grid-cols-2">
                            <x-forms.input.text name="email" id="email" label="{{ __('manager/auth.label_email') }}"
                                                placeholder="{{ __('manager/auth.placeholder_email') }}" required type="email"
                            />
                            <x-forms.input.text name="password" id="password" label="{{ __('manager/auth.label_password') }}"
                                                placeholder="{{ __('manager/auth.placeholder_password') }}" password="true" required type="password"
                            />
                        </div>
                        <x-forms.error-description message="{{ $errors->has('credentials') ? $errors->first('credentials') : '' }}" class="sm:col-span-2" />
                        <div class="flex items-center">
                            <x-forms.submit text="{{ __('manager/auth.login_button') }}" icon-left="fa-solid fa-right-to-bracket" submit="true" full-width="true" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="mr-auto place-self-center lg:col-span-6">
                <img class="hidden mx-auto lg:flex"
                     src="{{ asset('img/login-manager-image.png') }}"
                     alt="login-form-image">
            </div>
        </div>
    </section>
</x-layouts.app>
