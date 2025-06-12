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
                    <div class="flex flex-col items-start justify-center mt-4 space-y-2 sm:mt-0">
                        @foreach($footerElements as $element)
                            @if($element->isUrl())
                                <a href="{{ $element->text }}" class="text-gray-400 hover:text-gray-500 flex items-center space-x-2" target="_blank" rel="noopener noreferrer">
                                    <i class="{{ str_replace('-', ' fa-', $element->icon) }}"></i>
                                    <span>{{ $element->title }}</span>
                                </a>
                            @else
                                <span class="text-gray-400 flex items-center space-x-2">
                                    <i class="{{ str_replace('-', ' fa-', $element->icon) }}"></i>
                                    <span>{{ $element->text }}</span>
                                </span>
                            @endif
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
