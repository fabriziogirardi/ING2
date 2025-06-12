<div class="rounded-lg border border-blue-200 bg-white p-6 shadow-md hover:shadow-lg transition-shadow duration-300 ease-in-out hover:shadow-blue-400">
    <div class="w-full pb-4 relative">
        <img @class(['mx-auto', 'h-full', 'grayscale' => $product->hasStock(), 'w-64 object-cover']) src="{{ asset('bordeadora.jpg') }}" alt="{{ $product->name }}" />
        <button type="button" data-tooltip-target="tooltip-{{ $product->id }}" class="rounded-lg py-2 px-2.5 text-gray-500 hover:bg-gray-100 hover:text-blue-400 mb-4 bottom-1 right-1 absolute transition-colors duration-300 ease-in-out focus:outline-none focus:ring-4 focus:ring-blue-300 z-20">
            <i class="fa-solid fa-heart"></i>
        </button>
        <div id="tooltip-{{ $product->id }}" role="tooltip" class="tooltip invisible absolute z-10 inline-block rounded-lg bg-blue-400 px-3 py-2 text-sm font-medium text-white opacity-0 shadow-sm transition-opacity duration-300" data-popper-placement="top">
            Agregar a deseados
            <div class="tooltip-arrow" data-popper-arrow=""></div>
        </div>
    </div>
    <div class="flex flex-col gap-y-2">
        <h1 class="text-lg leading-tight font-bold text-gray-900">{{ $product->name }}</h1>
        <a href="{{ route('catalog.show', $product) }}" class="text-blue-400 hover:text-white border border-blue-400 hover:bg-blue-500 focus:ring-4 focus:outline-none focus:ring-yellow-200 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
            Ver más
        </a>
    </div>
</div>
