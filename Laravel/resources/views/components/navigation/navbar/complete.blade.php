<nav x-data="{ isOpen: false }" class="bg-gray-50">
    <div @keyup.ctrl.shift.enter.window="alert('HOLA')" class="px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex justify-between">
                <div>
                    <a href="{{ route('home') }}" class="flex items-center mr-5">
                        <img src="{{ asset('logotipo.png') }}" alt="Logo de la empresa" class="w-32 h-auto">
                    </a>
                </div>
                <x-search-bar />
            </div>
            <!-- Mobile Menu open: "block", Menu closed: "hidden" -->
            <div x-cloak :class="[isOpen ? 'translate-x-0 opacity-100 ' : 'opacity-0 -translate-x-full']" class="absolute inset-x-0 z-20 w-full px-6 py-4 transition-all duration-300 ease-in-out bg-white mt-4 lg:mt-0 lg:p-0 lg:top-0 lg:relative lg:bg-transparent lg:w-auto lg:opacity-100 lg:translate-x-0 lg:flex lg:items-center">
                <!-- Login and/or user actions -->
                <x-dynamic-component :component="'navigation.accounts.' . (Auth::getCurrentGuard() ?? 'guest') . '-menu'" class="flex flex-col -mx-6 lg:flex-row lg:items-center lg:mx-8" />
            </div>
        </div>
    </div>
</nav>



<!---->

<!-- Mobile menu button -->
<!-- <div class="flex lg:hidden">
    <button x-cloak @click="isOpen = !isOpen" type="button" class="text-gray-500 dark:text-gray-200 hover:text-gray-600 dark:hover:text-gray-400 focus:outline-none focus:text-gray-600 dark:focus:text-gray-400" aria-label="toggle menu">
        <svg x-show="!isOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 8h16M4 16h16" />
        </svg>

        <svg x-show="isOpen" xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
    </button>
</div>
</div> -->
