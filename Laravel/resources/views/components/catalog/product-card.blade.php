@props(['product', 'start_date', 'end_date'])

<div class="rounded-lg border border-blue-200 bg-white p-6 shadow-md hover:shadow-lg transition-shadow duration-300 ease-in-out hover:shadow-blue-400">
    <div class="w-full pb-4 relative h-64">
        <img @class(['mx-auto', 'h-full', 'grayscale' => count($product->branchesWithStockBetween($start_date, $end_date)) < 1, 'w-64 object-contain']) src="{{ $product->getFirstImage() }}" alt="{{ $product->name }}" />
    </div>
    <div class="flex flex-col gap-y-2">
        <h1 class="text-lg leading-tight font-bold text-gray-900">{{ $product->name }}</h1>
        <a href="{{ route('catalog.show', $product) }}" class="text-blue-400 hover:text-white border border-blue-400 hover:bg-blue-500 focus:ring-4 focus:outline-none focus:ring-yellow-200 font-semibold rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
            Ver más
        </a>
    </div>
</div>
