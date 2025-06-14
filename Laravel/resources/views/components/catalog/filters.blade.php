@props(['start_date', 'end_date'])
<div class="bg-gray-50 border-b border-gray-200">
    <div class="mx-auto max-w-3xl px-4 text-center sm:px-6 lg:max-w-7xl lg:px-8">
        <div class="py-8">
            <h1 class="text-3xl font-bold tracking-tight text-gray-900">Catálogo de Maquinarias</h1>
            <h2 class="mt-2">Descubre nuestra gran seleccion de maquinarias.</h2>
        </div>

        <section aria-labelledby="filter-heading" class="border-t border-gray-200">
            <h2 id="filter-heading" class="sr-only">Product filters</h2>
            <form action="" method="GET">
                <div class="flex flex-row justify-between items-center py-4">
                    <h1 class="font-bold text-lg">Fecha de alquiler</h1>
                    <div id="date-range-picker" date-rangepicker class="flex items-center justify-center">
                        <span class="mx-4 text-gray-500">Desde</span>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <i class="fa-regular fa-calendar"></i>
                            </div>
                            <input id="datepicker-range-start" name="start" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-400 focus:border-blue-500 block ps-10 p-2.5 " placeholder="Fecha de inicio" value='start_date'>
                        </div>
                        <span class="mx-4 text-gray-500">Hasta</span>
                        <div class="relative">
                            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                <i class="fa-regular fa-calendar"></i>
                            </div>
                            <input id="datepicker-range-end" name="end" type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-400 focus:border-blue-500 block ps-10 p-2.5 " placeholder="Fecha de fin" value="end_date">
                        </div>
                    </div>
                    <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-400 border border-transparent rounded-md shadow-sm hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fa-solid fa-magnifying-glass mr-2"></i>
                        Buscar
                    </button>
                </div>
            </form>
        </section>
    </div>
</div>
