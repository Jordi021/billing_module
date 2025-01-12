<?php

namespace App\Livewire\Actions;

use Illuminate\Support\Facades\Cookie;

class Logout {
    public function __invoke(): mixed {
        Cookie::queue(Cookie::forget('auth_and_user'));

        return redirect()
            ->route('login')
            ->with([
                'message' => 'Sesión cerrada correctamente.',
                'type' => 'success',
            ]);
    }
}
