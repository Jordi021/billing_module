<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Billing Module</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.1/css/all.min.css" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased font-sans">
    <!-- Helper for testing error pages -->
    {{-- {{abort(504)}} --}}

    <div class="bg-gray-50 text-black/50 dark:bg-black dark:text-white/50">
        <img id="background" class="absolute -left-20 top-0 max-w-[877px]" src={{ asset('svg/background.svg') }}
            alt="Background" />

        <div
            class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[#818cf8] selection:text-white">
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl">
                <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
                    <div class="flex lg:justify-center lg:col-start-2">
                        <x-application-logo class="size-12 sm:size-14 md:size-16 lg:size-20" />
                    </div>
                    @if (Route::has('login'))
                        <livewire:welcome.navigation />
                    @endif
                </header>
                <main class="mt-6">
                    <div class="grid gap-6 lg:grid-cols-2 lg:gap-8">
                        <div
                            class="flex flex-col gap-6 overflow-hidden rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#818cf8] md:row-span-3 lg:p-10 lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#818cf8]">
                            <h3 class="self-center text-lg font-semibold text-black dark:text-white/70">
                                {{ __('Members of group 2') }}</h3>
                            <div class="flex items-center gap-4">
                                <div
                                    class="relative inline-flex items-center justify-center w-10 h-10 overflow-hidden bg-gray-200 rounded-full dark:bg-gray-600">
                                    <i class="fas fa-user-graduate text-xl text-indigo-500 dark:text-indigo-300"></i>
                                </div>
                                <p>Jordan Steven Puruncajas Castillo</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <div
                                    class="relative inline-flex items-center justify-center w-10 h-10 overflow-hidden bg-gray-200 rounded-full dark:bg-gray-600">
                                    <i class="fas fa-user-graduate text-xl text-cyan-500 dark:text-cyan-300"></i>
                                </div>
                                <p>Diego David Recalde Varela</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <div
                                    class="relative inline-flex items-center justify-center w-10 h-10 overflow-hidden bg-gray-200 rounded-full dark:bg-gray-600">
                                    <i class="fas fa-user-graduate text-xl text-teal-500 dark:text-teal-300"></i>
                                </div>
                                <p>Melanie Rubi Ullco Tipan</p>
                            </div>
                            <div class="flex items-center gap-4">
                                <div
                                    class="relative inline-flex items-center justify-center w-10 h-10 overflow-hidden bg-gray-200 rounded-full dark:bg-gray-600">
                                    <i class="fas fa-user-graduate text-xl text-amber-500 dark:text-amber-300"></i>
                                </div>
                                 <p>Steven Mateo Garces Davila</p>
                            </div>
                        </div>

                        <div
                            class="flex items-start gap-4 rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#818cf8] lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#818cf8]">
                            <i class="fas fa-list text-2xl text-[#FF2D20]"></i>
                            <div>
                                <h3 class="text-lg font-semibold">{{ __('System Features') }}</h3>
                                <ul class="list-disc pl-5">
                                    <li>{{ __('Secure login with JWT') }}</li>
                                    <li>{{ __('Audit trails for user activity') }}</li>
                                    <li>{{ __('Client management') }}</li>
                                    <li>{{ __('Invoice reporting and management') }}</li>
                                </ul>
                            </div>
                        </div>

                        <div
                            class="flex items-start gap-4 rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#818cf8] lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#818cf8]">
                            <i class="fas fa-check-circle text-2xl text-green-500"></i>
                            <div>
                                <h3 class="text-lg font-semibold">{{ __('Key Requirements') }}</h3>
                                <p>{{ __('The module includes:') }}</p>
                                <ul class="list-disc pl-5">
                                    <li>{{ __('PDF invoice printing') }}</li>
                                    <li>{{ __('Stock validation before issuing invoices') }}</li>
                                    <li>{{ __('Client and invoice detail reporting') }}</li>
                                    <li>{{ __('Management of active/inactive clients') }}</li>
                                </ul>
                            </div>
                        </div>

                        <div
                            class="flex items-start gap-4 rounded-lg bg-white p-6 shadow-[0px_14px_34px_0px_rgba(0,0,0,0.08)] ring-1 ring-white/[0.05] transition duration-300 hover:text-black/70 hover:ring-black/20 focus:outline-none focus-visible:ring-[#818cf8] lg:pb-10 dark:bg-zinc-900 dark:ring-zinc-800 dark:hover:text-white/70 dark:hover:ring-zinc-700 dark:focus-visible:ring-[#818cf8]">
                            <i class="fas fa-database text-2xl text-blue-500"></i>
                            <div>
                                <h3 class="text-lg font-semibold">{{ __('Integration and Data') }}</h3>
                                <p>{{ __('Includes APIs for:') }}</p>
                                <ul class="list-disc pl-5">
                                    <li>{{ __('Stock inquiry') }}</li>
                                    <li>{{ __('Client and invoice listing') }}</li>
                                    <li>{{ __('Audit trails') }}</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </main>

                <footer class="py-16 text-center text-sm text-black dark:text-white/70">
                    Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                </footer>
            </div>
        </div>
    </div>
</body>

</html>
