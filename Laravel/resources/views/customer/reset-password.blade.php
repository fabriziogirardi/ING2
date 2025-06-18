<x-layouts.app>
    <x-slot:title>
        Cambiar contraseña
    </x-slot:title>

    <section class="bg-gray-50 h-full" x-data="{ submit: false }">
        <div class="grid max-w-screen-xl px-4 py-8 mx-auto lg:gap-20 lg:py-16 lg:grid-cols-12">
            <div class="w-full place-self-center lg:col-span-6">
                <div class="p-6 mx-auto bg-white rounded-lg shadow sm:max-w-xl sm:p-8">
                    <h1 class="mb-2 text-2xl font-bold leading-tight tracking-tight text-gray-900">
                        Cambiar Contraseña
                    </h1>
                    <p class="text-sm font-light text-gray-500">
                        Ingrese y confirme su nueva contraseña
                    </p>
                    <form class="mt-4 space-y-6 sm:mt-6" action="{{ route('customer.password.reset.post') }}" method="POST" @submit="submit = true">
                        @csrf
                        <div class="grid gap-x-6 gap-y-4 sm:grid-cols-2">
                            <x-forms.input.text name="new_password" id="new_password" label="Nueva Contraseña"
                                                placeholder="password123" required type="password"
                                                error="{{ $errors->has('new_password') ? $errors->first('new_password') : '' }}"
                            />
                            <x-forms.input.text name="new_password_confirmation" id="new_password_confirmation" label="Confirmar Nueva Contraseña"
                                                placeholder="password123" required type="password"
                                                error="{{ $errors->has('new_password_confirmation') ? $errors->first('new_password_confirmation') : '' }}"
                            />
                        </div>
                        @if($errors->has('error'))
                            <x-forms.error-description message="{{ $errors->first('error') }}" class="sm:col-span-2" />
                        @endif
                        <div class="flex items-center">
                            <x-forms.submit text="Confirmar cambio de contraseña" icon-left="fa-solid fa-right-to-bracket" submit="true" full-width="true" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="mr-auto place-self-center lg:col-span-6">
                <img class="hidden mx-auto lg:flex"
                     src="{{ asset('img/login-default-image.png') }}"
                     alt="login-default-image">
            </div>
        </div>
    </section>
</x-layouts.app>
