<footer class="mt-auto">
    <div class="p-4 py-8 bg-white md:p-8 lg:p-10 ">
        <div class="mx-auto max-w-screen-xl text-center">
            <div class="grid lg:grid-cols-3">
                <div class="flex">
                    <a href="#">
                        <img src="{{ asset('logotipo.png') }}" class="h-16 mr-3" alt="Logo">
                    </a>
                </div>
                <ul class="flex flex-wrap items-center mb-4 text-sm text-gray-500 lg:mb-0 mx-auto ">
                    <li>
                        <a href="#" class="mr-4 hover:underline md:mr-6 ">Acerca de nosotros</a>
                    </li>
                    <li>
                        <a href="#" class="mr-4 hover:underline md:mr-6">Política de privacidad</a>
                    </li>
                </ul>
                <div class="sm:items-center sm:justify-between sm:flex md:justify-end">
                    <div class="flex justify-center mt-4 space-x-6 sm:mt-0">
                        @foreach($footerElements as $element)
                            <a href="{{ $element->link }}" class="text-gray-500 hover:text-gray-900 dark:hover:text-white dark:text-gray-400">
                                <x-dynamic-component :component="$element->icon" class="w-4 h-4" />
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
            <hr class="my-6 border-gray-200 sm:mx-auto dark:border-gray-700 lg:my-8">
            <span class="block text-sm text-gray-500 dark:text-gray-400 md:col-span-3">© 2025 <a href="https://flowbite.com" class="hover:underline">TAGS</a>. Todos los derechos reservados.</span>
        </div>
    </div>
    <div class="pt-16"></div>
</footer>
