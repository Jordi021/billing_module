<x-error-layout :title="'Error ' . $status">
    <div class="text-center">
        <h1 class="text-9xl font-black text-gray-400 dark:text-gray-300">{{ $status }}</h1>

        <p class="text-2xl font-bold tracking-tight text-gray-900 sm:text-4xl dark:text-gray-500">{{ __('Uh-oh!') }}</p>

        <p class="mt-4 text-gray-500 dark:text-gray-100">{{ __($message) }}</p>


        <div class="flex justify-center gap-2 mt-4">
            <a href="javascript:history.back()"
                class="flex items-center gap-3 rounded bg-gray-600 px-5 py-3 text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring ring-gray-300">
                <i class="fa-solid fa-arrow-left"></i>
                {{ __('Go back') }}
            </a>
            <a href="{{ route('welcome') }}"
                class="flex items-center gap-3 rounded bg-indigo-600 px-5 py-3 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring ring-indigo-300">
                <i class="fa-solid fa-house"></i>
                {{ __('Go Home') }}
            </a>
        </div>

    </div>
</x-error-layout>
