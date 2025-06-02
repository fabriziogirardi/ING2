<div class="z-10 hover:bg-black/30 transition-colors duration-500 ease-in-out p-6 rounded-xl">
    <time datetime="2021-08" class="flex items-center text-lg font-semibold leading-6">
        <div class="bg-white rounded-full w-2.5 h-2.5 mr-2"></div>
        {{ $date }}
        <div class="absolute -ml-2 h-px w-screen -translate-x-full bg-white/50 sm:-ml-4 lg:static lg:-mr-6 lg:ml-8 lg:w-auto lg:flex-auto lg:translate-x-0" aria-hidden="true"></div>
    </time>
    <p class="mt-6 text-lg font-semibold leading-8"> {{ $title  }} </p>
    <p class="mt-1 text-base leading-7 "> {{ $description }} </p>
</div>
