<div class="bg-gray-50 border-b border-gray-200">
    <div class="mx-auto max-w-3xl px-4 text-center sm:px-6 lg:max-w-7xl lg:px-8">
        <div class="py-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Catálogo de Maquinarias</h1>
            <h2 class="mt-2">Descubre nuestra gran seleccion de maquinarias.</h2>
        </div>

        <section aria-labelledby="filter-heading" class="border-t border-gray-200">
            <h2 id="filter-heading" class="sr-only">Product filters</h2>
            <div class="flex flex-row justify-between items-center py-4">
                <h1 class="font-bold text-lg">Fecha de alquiler</h1>
                <div id="date-range-picker" date-rangepicker class="flex items-center justify-center">
                    <span class="mx-4 text-gray-500">Desde</span>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <i class="fa-regular fa-calendar"></i>
                        </div>
                        <input id="datepicker-range-start" name="start" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-400 focus:border-blue-500 block ps-10 p-2.5 " placeholder="Fecha de inicio">
                    </div>
                    <span class="mx-4 text-gray-500">Hasta</span>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                            <i class="fa-regular fa-calendar"></i>
                        </div>
                        <input id="datepicker-range-end" name="end" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-400 focus:border-blue-500 block ps-10 p-2.5 " placeholder="Fecha de fin">
                    </div>
                </div>
            </div>

            <div>
                <form class="flex flex-wrap justify-center mt-4 mx-32 gap-x-1">
                    <input type="button" class="text-blue-500 hover:text-white border border-blue-500 hover:bg-blue-500 focus:ring-4 focus:outline-none focus:ring-yellow-200 font-medium rounded-full text-sm px-5 py-1 text-center mb-2 me-1 transition-colors duration-300" value="Pepe"/>
                </form>
            </div>

            <div class="flex items-center justify-between mb-3">

                <!-- Mobile filter dialog toggle, controls the 'mobileFilterDialogOpen' state. -->
                <button type="button" class="inline-block text-sm font-medium text-gray-700 hover:text-gray-900 sm:hidden">Filters</button>

                <div class="hidden sm:flex sm:items-baseline sm:space-x-8">
                    <div class="relative inline-block text-left">
                        <div>
                            <button data-dropdown-toggle="brandDropdown" type="button" class="group inline-flex items-center justify-center text-md font-bold text-gray-500 hover:text-gray-900" aria-expanded="false">
                                <span>Marca</span>
                                <i class="fa-solid fa-chevron-down ml-1"></i>
                            </button>
                        </div>
                        <div id="brandDropdown" class="hidden right-0 z-10 mt-2 origin-top-right rounded-md bg-white p-4 shadow-2xl ring-1 ring-opacity-5 focus:outline-none">
                            <form class="space-y-4">
                                <div class="flex items-center">
                                    <input id="filter-brand-0" name="" value="" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-400 focus:ring-yellow-400">
                                    <label for="filter-brand-0" class="ml-3 whitespace-nowrap pr-6 text-sm font-medium text-gray-900">Marca</label>
                                    <input id="filter-brand-0" name="" value="" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-400 focus:ring-yellow-400">
                                    <label for="filter-brand-0" class="ml-3 whitespace-nowrap pr-6 text-sm font-medium text-gray-900">Marca</label>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!--
      Mobile filter dialog

      Off-canvas menu for mobile, show/hide based on off-canvas menu state.
    -->
    <div class="relative z-40 sm:hidden" role="dialog" aria-modal="true">
        <!--
          Off-canvas menu backdrop, show/hide based on off-canvas menu state.

          Entering: "transition-opacity ease-linear duration-300"
            From: "opacity-0"
            To: "opacity-100"
          Leaving: "transition-opacity ease-linear duration-300"
            From: "opacity-100"
            To: "opacity-0"
        -->
        <div class="fixed inset-0 bg-black bg-opacity-25" aria-hidden="true"></div>

        <div class="fixed inset-0 z-40 flex">
            <!--
              Off-canvas menu, show/hide based on off-canvas menu state.

              Entering: "transition ease-in-out duration-300 transform"
                From: "translate-x-full"
                To: "translate-x-0"
              Leaving: "transition ease-in-out duration-300 transform"
                From: "translate-x-0"
                To: "translate-x-full"
            -->
            <div class="relative ml-auto flex h-full w-full max-w-xs flex-col overflow-y-auto bg-white py-4 pb-6 shadow-xl">
                <div class="flex items-center justify-between px-4">
                    <h2 class="text-lg font-medium text-gray-900">Filters</h2>
                    <button type="button" class="-mr-2 flex h-10 w-10 items-center justify-center rounded-md bg-white p-2 text-gray-400 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <span class="sr-only">Close menu</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Filters -->
                <form class="mt-4">
                    <div class="border-t border-gray-200 px-4 py-6">
                        <h3 class="-mx-2 -my-3 flow-root">
                            <!-- Expand/collapse question button -->
                            <button type="button" class="flex w-full items-center justify-between bg-white px-2 py-3 text-sm text-gray-400" aria-controls="filter-section-0" aria-expanded="false">
                                <span class="font-medium text-gray-900">Category</span>
                                <span class="ml-6 flex items-center">
                  <!--
                    Expand/collapse icon, toggle classes based on question open state.

                    Open: "-rotate-180", Closed: "rotate-0"
                  -->
                  <svg class="h-5 w-5 rotate-0 transform" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                  </svg>
                </span>
                            </button>
                        </h3>
                        <div class="pt-6" id="filter-section-0">
                            <div class="space-y-6">
                                <div class="flex items-center">
                                    <input id="filter-mobile-category-0" name="category[]" value="tees" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <label for="filter-mobile-category-0" class="ml-3 text-sm text-gray-500">Tees</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="filter-mobile-category-1" name="category[]" value="crewnecks" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <label for="filter-mobile-category-1" class="ml-3 text-sm text-gray-500">Crewnecks</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="filter-mobile-category-2" name="category[]" value="hats" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <label for="filter-mobile-category-2" class="ml-3 text-sm text-gray-500">Hats</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-6">
                        <h3 class="-mx-2 -my-3 flow-root">
                            <!-- Expand/collapse question button -->
                            <button type="button" class="flex w-full items-center justify-between bg-white px-2 py-3 text-sm text-gray-400" aria-controls="filter-section-1" aria-expanded="false">
                                <span class="font-medium text-gray-900">Brand</span>
                                <span class="ml-6 flex items-center">
                  <!--
                    Expand/collapse icon, toggle classes based on question open state.

                    Open: "-rotate-180", Closed: "rotate-0"
                  -->
                  <svg class="h-5 w-5 rotate-0 transform" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                  </svg>
                </span>
                            </button>
                        </h3>
                        <div class="pt-6" id="filter-section-1">
                            <div class="space-y-6">
                                <div class="flex items-center">
                                    <input id="filter-mobile-brand-0" name="brand[]" value="clothing-company" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <label for="filter-mobile-brand-0" class="ml-3 text-sm text-gray-500">Clothing Company</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="filter-mobile-brand-1" name="brand[]" value="fashion-inc" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <label for="filter-mobile-brand-1" class="ml-3 text-sm text-gray-500">Fashion Inc.</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="filter-mobile-brand-2" name="brand[]" value="shoes-n-more" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <label for="filter-mobile-brand-2" class="ml-3 text-sm text-gray-500">Shoes &#039;n More</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-6">
                        <h3 class="-mx-2 -my-3 flow-root">
                            <!-- Expand/collapse question button -->
                            <button type="button" class="flex w-full items-center justify-between bg-white px-2 py-3 text-sm text-gray-400" aria-controls="filter-section-2" aria-expanded="false">
                                <span class="font-medium text-gray-900">Color</span>
                                <span class="ml-6 flex items-center">
                  <!--
                    Expand/collapse icon, toggle classes based on question open state.

                    Open: "-rotate-180", Closed: "rotate-0"
                  -->
                  <svg class="h-5 w-5 rotate-0 transform" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                  </svg>
                </span>
                            </button>
                        </h3>
                        <div class="pt-6" id="filter-section-2">
                            <div class="space-y-6">
                                <div class="flex items-center">
                                    <input id="filter-mobile-color-0" name="color[]" value="white" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <label for="filter-mobile-color-0" class="ml-3 text-sm text-gray-500">White</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="filter-mobile-color-1" name="color[]" value="black" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <label for="filter-mobile-color-1" class="ml-3 text-sm text-gray-500">Black</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="filter-mobile-color-2" name="color[]" value="grey" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <label for="filter-mobile-color-2" class="ml-3 text-sm text-gray-500">Grey</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="border-t border-gray-200 px-4 py-6">
                        <h3 class="-mx-2 -my-3 flow-root">
                            <!-- Expand/collapse question button -->
                            <button type="button" class="flex w-full items-center justify-between bg-white px-2 py-3 text-sm text-gray-400" aria-controls="filter-section-3" aria-expanded="false">
                                <span class="font-medium text-gray-900">Sizes</span>
                                <span class="ml-6 flex items-center">
                  <!--
                    Expand/collapse icon, toggle classes based on question open state.

                    Open: "-rotate-180", Closed: "rotate-0"
                  -->
                  <svg class="h-5 w-5 rotate-0 transform" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                  </svg>
                </span>
                            </button>
                        </h3>
                        <div class="pt-6" id="filter-section-3">
                            <div class="space-y-6">
                                <div class="flex items-center">
                                    <input id="filter-mobile-sizes-0" name="sizes[]" value="s" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <label for="filter-mobile-sizes-0" class="ml-3 text-sm text-gray-500">S</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="filter-mobile-sizes-1" name="sizes[]" value="m" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <label for="filter-mobile-sizes-1" class="ml-3 text-sm text-gray-500">M</label>
                                </div>
                                <div class="flex items-center">
                                    <input id="filter-mobile-sizes-2" name="sizes[]" value="l" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                                    <label for="filter-mobile-sizes-2" class="ml-3 text-sm text-gray-500">L</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

