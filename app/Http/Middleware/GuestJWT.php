<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestJWT {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        $authCookie = $request->cookie('auth_and_user');

        if ($authCookie) {
            $authData = json_decode($authCookie, false);

            if ($authData && isset($authData->auth_token)) {
                return redirect()->back();
            }
        }

        return $next($request);
    }
}
