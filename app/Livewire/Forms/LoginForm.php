<?php

namespace App\Livewire\Forms;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Support\Facades\Cookie;

class LoginForm extends Form {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    // #[Validate('boolean')]
    // public bool $remember = false;

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void {
        $this->ensureIsNotRateLimited();

        try {
            $response = Http::post(
                'https://seri-api-utn-2024.fly.dev/api/login',
                [
                    'email' => $this->email,
                    'password' => $this->password,
                ]
            );

            if (!$response->successful()) {
                throw ValidationException::withMessages([
                    'form.email' =>
                        'Las credenciales proporcionadas son incorrectas.',
                ]);
            }

            $data = $response->json();
            $token = $data['token'];

            if (!str_contains($token, '.')) {
                throw ValidationException::withMessages([
                    'form.email' => 'El token JWT recibido es inválido.',
                ]);
            }

            $tokenParts = explode('.', $token);
            if (count($tokenParts) !== 3) {
                throw ValidationException::withMessages([
                    'form.email' => 'El token JWT recibido es inválido.',
                ]);
            }

            $payload = json_decode(base64_decode($tokenParts[1]));

            if (!$payload) {
                throw ValidationException::withMessages([
                    'form.email' => 'El payload del token JWT es inválido.',
                ]);
            }

            $cookieValue = json_encode([
                'auth_token' => $token,
                'user_info' => [
                    'email' => $payload->email ?? null,
                    'id' => $payload->id ?? null,
                    'name' => $payload->name ?? 'User',
                    'roles' => $payload->roles ?? [],
                    'permissions' => $payload->permissions ?? [],
                    'exp' => $payload->exp ?? null,
                ],
            ]);

            Cookie::queue(
                'auth_and_user',
                $cookieValue,
                30,
                '/',
                null,
                config('app.env') === 'production',
                true
            );

            RateLimiter::clear($this->throttleKey());
        } catch (\Exception $e) {
            RateLimiter::hit($this->throttleKey());
            $this->reset('password');
            throw ValidationException::withMessages([
                'form.email' => 'Credenciales no validas.',
            ]);
        }
    }

    /**
     * Ensure the authentication request is not rate limited.
     */
    protected function ensureIsNotRateLimited(): void {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'form.email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the authentication rate limiting throttle key.
     */
    protected function throttleKey(): string {
        return Str::transliterate(
            Str::lower($this->email) . '|' . request()->ip()
        );
    }
}
