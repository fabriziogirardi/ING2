<x-layouts.app>
    <x-slot:title>
        Devolución de maquinaria
    </x-slot:title>

    <section class="bg-gray-50 h-full" x-data="{ submit: false }">
        <div class="grid max-w-screen-xl px-4 py-8 mx-auto lg:gap-20 lg:py-16 lg:grid-cols-12">
            <div class="w-full place-self-center lg:col-span-6">
                <div class="p-6 mx-auto bg-white rounded-lg shadow sm:max-w-xl sm:p-8">
                    <h1 class="mb-2 text-2xl font-bold leading-tight tracking-tight text-gray-900">
                        Devolución de maquinaria
                    </h1>
                    <p class="text-sm font-light text-gray-500">
                        Ingrese los datos de la reserva para realizar su devolución
                    </p>
                    <form class="mt-4 space-y-6 sm:mt-6" action="{{ route('employee.reservation.return.store') }}" method="POST" @submit="submit = true"
                          x-data="{
                              rating: '{{ old('rating') }}',
                          }"
                    >
                        @csrf
                        <div class="grid gap-x-6 gap-y-4 sm:grid-cols-2">
                            <x-forms.input.select
                                name="government_id_type_id"
                                label="Tipo de Documento"
                                :options="$governmentIdType"
                                :old="old('government_id_type_id')"
                                placeholder="Seleccionar tipo de documento"
                                required
                            />
                            <x-forms.input.text name="government_id_number" id="government_id_number" label="Número de Documento"
                                                placeholder="12345678" required type="text"
                                                error="{{ $errors->has('government_id_number') ? $errors->first('government_id_number') : '' }}"
                            />
                            <x-forms.input.text name="code" id="code" label="Codigo de Reserva"
                                                placeholder="ABD2JD21" required type="text"
                                                error="{{ $errors->has('code') ? $errors->first('code') : '' }}"
                            />
                            <x-forms.input.select
                                name="rating"
                                id="rating"
                                label="Puntaje"
                                :options="[
                                        ['id' => 0, 'name' => '0'],
                                        ['id' => 1, 'name' => '1'],
                                        ['id' => 2, 'name' => '2'],
                                        ['id' => 3, 'name' => '3'],
                                        ['id' => 4, 'name' => '4'],
                                        ['id' => 5, 'name' => '5'],
                                    ]"
                                :old="old('rating')"
                                placeholder="Seleccionar puntaje"
                                x-model="rating"
                                required
                            />
                            <div class="sm:col-span-2">
                                <label for="description" class="block mb-2 text-sm font-medium text-gray-900">
                                    Descripción
                                </label>
                                <input type="text" id="description" name="description"
                                       class="text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 bg-gray-50 border border-gray-300"
                                       placeholder="Describe tu experiencia"
                                       x-bind:required="rating && parseInt(rating) <= 2"
                                       value="{{ old('description') }}"
                                >
                                @error('description')
                                    <div class="opacity-100">
                                        <x-forms.error-description message="{{ $message }}" />
                                    </div>
                                @enderror
                            </div>
                        </div>
                        @if($errors->has('error'))
                            <x-forms.error-description message="{{ $errors->first('error') }}" class="sm:col-span-2" />
                        @endif
                        <div class="flex items-center">
                            <x-forms.submit text="Marcar reserva como devuelta" icon-left="fa-solid fa-right-to-bracket" submit="true" full-width="true" />
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
