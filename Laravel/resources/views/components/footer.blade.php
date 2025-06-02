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
                                <a href="#">
                                    <svg class="w-[24px] h-[24px] text-gray-800" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                        <g id="SVGRepo_iconCarrier">
                                            <path d="M20 12.05C19.9813 10.5255 19.5273 9.03809 18.6915 7.76295C17.8557 6.48781 16.673 5.47804 15.2826 4.85257C13.8921 4.2271 12.3519 4.01198 10.8433 4.23253C9.33473 4.45309 7.92057 5.10013 6.7674 6.09748C5.61422 7.09482 4.77005 8.40092 4.3343 9.86195C3.89856 11.323 3.88938 12.8781 4.30786 14.3442C4.72634 15.8103 5.55504 17.1262 6.69637 18.1371C7.83769 19.148 9.24412 19.8117 10.75 20.05V14.38H8.75001V12.05H10.75V10.28C10.7037 9.86846 10.7483 9.45175 10.8807 9.05931C11.0131 8.66687 11.23 8.30827 11.5161 8.00882C11.8022 7.70936 12.1505 7.47635 12.5365 7.32624C12.9225 7.17612 13.3368 7.11255 13.75 7.14003C14.3498 7.14824 14.9482 7.20173 15.54 7.30003V9.30003H14.54C14.3676 9.27828 14.1924 9.29556 14.0276 9.35059C13.8627 9.40562 13.7123 9.49699 13.5875 9.61795C13.4627 9.73891 13.3667 9.88637 13.3066 10.0494C13.2464 10.2125 13.2237 10.387 13.24 10.56V12.07H15.46L15.1 14.4H13.25V20C15.1399 19.7011 16.8601 18.7347 18.0985 17.2761C19.3369 15.8175 20.0115 13.9634 20 12.05Z"></path>
                                        </g>
                                    </svg>
                                </a>
                            </x-slot:icon>
                            Facebook
                        </x-footer.item-with-icon>
                    </ul>
                </div>
            </div>
            <div class="text-center py-2 text-gray-300">TAGS 2025 - <a>tags.com.ar - Todos los derechos reservados</a></div>
        </div>
    </footer>
</div>
