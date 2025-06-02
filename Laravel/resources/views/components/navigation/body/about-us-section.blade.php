<section class="bg-white text-white">
    <div class="h-[100vh]">
        <img src="{{ asset('img/about-us.png') }}" alt="About us image" class="mx-auto w-full h-[100vh] object-cover absolute shadow-2xl shadow-blue-600/50"/>
        <div class="text-white text-center absolute flex flex-col items-center justify-center w-full h-full">
            <h1 class="text-8xl font-bold z-20">Sobre Nosotros</h1>
            <p class="mt-4 z-20 text-2xl font-medium max-w-3xl mx-auto">
                Somos una empresa dedicada a la venta de maquinarias de uso general, construcciones, agricultura, trabajos domesticos. Ofrecemos una amplia gama de productos de alta calidad para satisfacer las necesidades de nuestros clientes.
            </p>

            <h2 class="font-bold text-5xl mt-24">Nuestra Historia.</h2>
            <x-navigation.body.timeline />
        </div>
    </div>
</section>
