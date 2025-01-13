<nav class="-mx-3 flex flex-1 justify-end items-center">
    @if (Cookie::has('auth_and_user'))
        <a
            href="{{ route('dashboard') }}"
            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
        >
            {{ __('Module') }}
        </a>
    @else
        <a
            href="{{ route('login') }}"
            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white"
        >
            {{ __('Log in') }}
        </a>
    @endauth
    <x-toggle-theme-button />
</nav>
