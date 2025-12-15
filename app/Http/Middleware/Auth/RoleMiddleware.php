<?php

namespace App\Http\Middleware\Auth;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Enums
use App\Enums\RoleEnum;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(401, 'Unautenticated');
        }

        $allowedEnums = [];
        foreach ($roles as $roleValue) {
            $enum = RoleEnum::tryFrom($roleValue);
            if (!$enum) {
                abort(500, "Configuration Error: Invalid role '{$roleValue}' passed to RoleMiddleware.");
            }
            $allowedEnums[] = $enum;
        }

        if (!in_array($user->role, $allowedEnums, true)) {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
