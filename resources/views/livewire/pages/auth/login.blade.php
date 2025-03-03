<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;

use function Livewire\Volt\form;
use function Livewire\Volt\layout;

view()->share('title', __('Log in'));
layout('layouts.guest');
form(LoginForm::class);

$login = function () {
    $this->validate();

    $this->form->authenticate();

    Session::regenerate();

    $this->redirectIntended(default: route('dashboard', absolute: false), navigate: false);
};
?>

<div class="mt-2">
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form wire:submit="login">
        <!-- Email Address -->
        <div>
            <x-text-float-input wire:model="form.email" id="email" type="email" name="email"
                label="{{ __('Email') }}" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('form.email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-text-float-input wire:model="form.password" id="password" type="password" name="password"
                label="{{ __('Password') }}" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">

            <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                href="{{ url('/') }}">
                {{ __('Go back') }}
            </a>
            <x-primary-button class="ms-3 flex items-center gap-2" wire:loading.attr="disabled"
                wire:loading.class="opacity-50 cursor-not-allowed">
                {{ __('Log in') }}
                <div wire:loading wire:target="login">
                    <x-loading-spinner color="black" />
                </div>
            </x-primary-button>

        </div>
    </form>
</div>
