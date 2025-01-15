<?php

use App\Livewire\Actions\Logout;
use App\Models\User;

$logout = function (Logout $logout) {
    $logout();
    $this->redirect('/', navigate: false);
};
?>

<nav class="-mx-3 flex flex-1 justify-end items-center">
    @if (Cookie::has('auth_and_user'))
        <a href="{{ route('dashboard') }}"
            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/50 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/50 dark:focus-visible:ring-white">
            {{ __('Module') }}
        </a>
        <a wire:click="logout"
            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/50 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/50 dark:focus-visible:ring-white cursor-pointer">
            {{ __('Logout') }}
        </a>
    @else
        <a href="{{ route('login') }}"
            class="rounded-md px-3 py-2 text-black ring-1 ring-transparent transition hover:text-black/70 focus:outline-none focus-visible:ring-[#FF2D20] dark:text-white dark:hover:text-white/80 dark:focus-visible:ring-white">
            {{ __('Log in') }}
        </a>
    @endauth
    <x-toggle-theme-button />
</nav>
