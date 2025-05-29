<x-layouts.app>
    <x-slot:title>
        Login
    </x-slot:title>

    <section class="bg-gray-50 h-full">
        <div class="grid max-w-screen-xl px-4 py-8 mx-auto lg:gap-20 lg:py-16 lg:grid-cols-12">
            <div class="w-full place-self-center lg:col-span-6">
                <div class="p-6 mx-auto bg-white rounded-lg shadow sm:max-w-xl sm:p-8">
                    <a href="#"
                       class="inline-flex items-center mb-4 text-xl font-semibold text-gray-900">
                        <img class="w-8 h-8 mr-2" src="https://flowbite.s3.amazonaws.com/blocks/marketing-ui/logo.svg"
                             alt="logo">
                        Flowbite
                    </a>
                    <h1 class="mb-2 text-2xl font-bold leading-tight tracking-tight text-gray-900">
                        Welcome back
                    </h1>
                    <p class="text-sm font-light text-gray-500">
                        Start your website in seconds. Don’t have an account? <a href="#"
                                                                                 class="font-medium text-primary-600 hover:underline">Sign up</a>.
                    </p>
                    <form class="mt-4 space-y-6 sm:mt-6" action="{{ route('customer.login') }}" method="POST">
                        @csrf
                        <div class="grid gap-6 sm:grid-cols-2">
                            <div>
                                <label for="email"
                                       class="block mb-2 text-sm font-medium text-gray-900">Email</label>
                                <input type="email" name="email" id="email"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                       placeholder="name@company.com" required="">
                            </div>
                            <div>
                                <label for="password"
                                       class="block mb-2 text-sm font-medium text-gray-900">Password</label>
                                <input type="password" name="password" id="password" placeholder="••••••••"
                                       class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5"
                                       required="">
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <a href="#"
                               class="text-sm font-medium text-primary-600 hover:underline">Forgot
                                password?</a>
                        </div>
                        <button type="submit"
                                class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Sign
                            in to your account</button>
                    </form>
                </div>
            </div>
            <div class="mr-auto place-self-center lg:col-span-6">
                <img class="hidden mx-auto lg:flex"
                     src="{{ asset('img/login-form-image.png') }}"
                     alt="login-form-image">
            </div>
        </div>
    </section>
</x-layouts.app>
