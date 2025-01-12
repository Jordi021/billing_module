<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateRoleAndPermission {
    /**
     * Handle an incoming request.
     */
    public function handle(
        Request $request,
        Closure $next,
        $requiredRoles = null,
        $requiredPermissions = null
    ) {
        $user = $request->get('user');

        if (!$user) {
            return redirect('/login')->withErrors('No estás autenticado.');
        }

        // Validar roles si están definidos
        if ($requiredRoles) {
            $roles = explode('|', $requiredRoles); // Convertir roles en array
            if (!array_intersect($user['roles'], $roles)) {
                abort(
                    403,
                    'No tienes el rol necesario para acceder a esta ruta.'
                );
            }
        }

        // Validar permisos si están definidos
        if ($requiredPermissions) {
            $permissions = explode('|', $requiredPermissions); // Convertir permisos en array
            if (!array_intersect($user['permissions'], $permissions)) {
                abort(
                    403,
                    'No tienes el permiso necesario para acceder a esta ruta.'
                );
            }
        }

        return $next($request);
    }
}
