<div class="flex items-center gap-x-4">
    <x-navigation.navbar.account-badge :person="auth()->guard('employee')->user()->person" user-type="{{ __('auth.role_employee') }}" />

    <div class="flex flex-row gap-x-4">
        <div>
            <button id="dropdownDividerButton" data-dropdown-toggle="dropdownDivider" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
                Menu
                <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                </svg>
            </button>
            <div id="dropdownDivider" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow-sm w-44 dark:bg-gray-700 dark:divide-gray-600">
                <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDividerButton">
                    <li>
                        <a href="{{ route('employee.register_customer') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Alta de Cliente</a>
                    </li>
                    <li>
                        <a href="{{ route('employee.reservation.retire') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Entregar Maquinaria</a>
                    </li>
                    <li>
                        <a href="{{ route('employee.cancel-reservation') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Cancelar Reservacion</a>
                    </li>
                    <li>
                        <a href="{{ route('employee.reservation.return') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Devolver Maquinaria</a>
                    </li>
                </ul>
                <div class="py-2">
                    <a href="{{ route('employee.logout') }}" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-100 dark:hover:bg-red-600 dark:text-red-400 dark:hover:text-white">
                        {{ __('employee/auth.logout_button') }}
                    </a>
                </div>
            </div>
        </div>
        <div>
            <x-navigation.navbar.forum-button />
        </div>
    </div>
</div>
