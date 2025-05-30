<div class="bottom-0">
    <footer class="bg-white">
        <div class="container mx-auto">
            <div class="w-full flex flex-col md:flex-row py-6">
                <div class="flex flex-row mr-3">
                    <img src="{{ asset('imagotipo.png')}}" class="h-[15rem] w-[15rem] object-cover" alt="Logotipo de la empresa Alkil.ar">
                    <p class="pt-5 w-40">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam quis fermentum tellus, sit amet fermentum erat. Pellentesque a laoreet mi, tempus malesuada velit.</p>
                </div>
                <x-footer.vertical-bar></x-footer.vertical-bar>
                <div class="flex-1 px-5">
                    <x-footer.title>Inicio</x-footer.title>
                    <ul class="list-none mb-6 space-y-2">
                        <x-footer.item>Maquinarias</x-footer.item>
                        <x-footer.item>Sobre Nosotros</x-footer.item>
                        <x-footer.item>Mapa de Sucursales</x-footer.item>
                    </ul>
                </div>
                <x-footer.vertical-bar></x-footer.vertical-bar>
                <div class="flex-1 px-5">
                    <x-footer.title>Links de Interes</x-footer.title>
                    <ul class="list-none mb-6 space-y-2">
                        <x-footer.item>Foro</x-footer.item>
                        <x-footer.item>Politica de Privacidad</x-footer.item>
                    </ul>
                </div>
                <x-footer.vertical-bar></x-footer.vertical-bar>
                <div class="flex-1 px-5">
                    <x-footer.title>Contacto</x-footer.title>
                    <ul class="list-reset mb-6 space-y-2">
                        <x-footer.item-with-icon>
                            <x-slot:icon>
                                <svg class="w-[24px] h-[24px] text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.427 14.768 17.2 13.542a1.733 1.733 0 0 0-2.45 0l-.613.613a1.732 1.732 0 0 1-2.45 0l-1.838-1.84a1.735 1.735 0 0 1 0-2.452l.612-.613a1.735 1.735 0 0 0 0-2.452L9.237 5.572a1.6 1.6 0 0 0-2.45 0c-3.223 3.2-1.702 6.896 1.519 10.117 3.22 3.221 6.914 4.745 10.12 1.535a1.601 1.601 0 0 0 0-2.456Z"/>
                                </svg>
                            </x-slot:icon>
                            Tel: 123123123
                        </x-footer.item-with-icon>
                        <x-footer.item-with-icon>
                            <x-slot:icon>
                                <svg class="w-[24px] h-[24px] text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-width="1.5" d="m3.5 5.5 7.893 6.036a1 1 0 0 0 1.214 0L20.5 5.5M4 19h16a1 1 0 0 0 1-1V6a1 1 0 0 0-1-1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1Z"/>
                                </svg>
                            </x-slot:icon>
                            contacto@alkil.ar
                        </x-footer.item-with-icon>
                    </ul>
                </div>
                <x-footer.vertical-bar></x-footer.vertical-bar>
                <div class="flex-1 px-5">
                    <x-footer.title>Seguinos en redes</x-footer.title>
                    <ul class="list-reset mb-6 space-y-2">
                        <x-footer.item-with-icon>
                            <x-slot:icon>
                                <svg class="w-[24px] h-[24px] text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                                    <path fill="currentColor" fill-rule="evenodd" d="M3 8a5 5 0 0 1 5-5h8a5 5 0 0 1 5 5v8a5 5 0 0 1-5 5H8a5 5 0 0 1-5-5V8Zm5-3a3 3 0 0 0-3 3v8a3 3 0 0 0 3 3h8a3 3 0 0 0 3-3V8a3 3 0 0 0-3-3H8Zm7.597 2.214a1 1 0 0 1 1-1h.01a1 1 0 1 1 0 2h-.01a1 1 0 0 1-1-1ZM12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6Zm-5 3a5 5 0 1 1 10 0 5 5 0 0 1-10 0Z" clip-rule="evenodd"/>
                                </svg>
                            </x-slot:icon>
                            Instagram
                        </x-footer.item-with-icon>
                        <x-footer.item-with-icon>
                            <x-slot:icon>
                                <svg class="w-[24px] h-[24px] text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M12.51 8.796v1.697a3.738 3.738 0 0 1 3.288-1.684c3.455 0 4.202 2.16 4.202 4.97V19.5h-3.2v-5.072c0-1.21-.244-2.766-2.128-2.766-1.827 0-2.139 1.317-2.139 2.676V19.5h-3.19V8.796h3.168ZM7.2 6.106a1.61 1.61 0 0 1-.988 1.483 1.595 1.595 0 0 1-1.743-.348A1.607 1.607 0 0 1 5.6 4.5a1.601 1.601 0 0 1 1.6 1.606Z" clip-rule="evenodd"/>
                                    <path d="M7.2 8.809H4V19.5h3.2V8.809Z"/>
                                </svg>
                            </x-slot:icon>
                            LinkedIn
                        </x-footer.item-with-icon>
                        <x-footer.item-with-icon>
                            <x-slot:icon>
                                <svg class="w-[24px] h-[24px] text-gray-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M13.135 6H15V3h-1.865a4.147 4.147 0 0 0-4.142 4.142V9H7v3h2v9.938h3V12h2.021l.592-3H12V6.591A.6.6 0 0 1 12.592 6h.543Z" clip-rule="evenodd"/>
                                </svg>
                            </x-slot:icon>
                            Facebook
                        </x-footer.item-with-icon>
                    </ul>
                </div>
            </div>
            <div class="text-center py-2 text-gray-300">TAGS 2025 - <a>tags.com.ar</a></div>
        </div>
    </footer>
</div>
