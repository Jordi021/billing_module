<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtAuth {
    protected $except = ['/', 'login', 'register'];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        try {
            // Obtener la cookie que contiene el token y los datos del usuario
            $authCookie = $request->cookie('auth_and_user');

            if (!$authCookie) {
                throw new \Exception('No auth cookie found');
            }

            // Decodificar la cookie
            $authData = json_decode($authCookie, false);

            if (!isset($authData->auth_token) || !isset($authData->user_info)) {
                throw new \Exception('Invalid auth data structure');
            }

            if (empty($authData->auth_token)) {
                throw new \Exception('Empty auth token');
            }

            if (
                isset($authData->user_info->exp) &&
                $authData->user_info->exp < time()
            ) {
                return redirect('/login')->with(
                    'error',
                    'El token JWT ha expirado. Por favor, inicia sesión nuevamente.'
                );
            }

            // Almacenar los datos del usuario en el request para uso posterior
            $request->merge([
                'user' => [
                    'id' => $authData->user_info->id ?? null,
                    'name' => $authData->user_info->name ?? 'User',
                    'email' => $authData->user_info->email ?? null,
                    'roles' => $authData->user_info->roles ?? [],
                    'permissions' => $authData->user_info->permissions ?? [],
                ],
            ]);

            return $next($request);
        } catch (\Exception $e) {
            return redirect()
                ->route('login')
                ->with([
                    'message' => 'Por favor, inicia sesión nuevamente.',
                    'type' => 'error',
                ]);
        }
    }
}
