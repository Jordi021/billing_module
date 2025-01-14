<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Cookie;

class Logout {
    public function __invoke(): void {
        Cookie::queue(Cookie::forget('auth_and_user'));
    }
}
