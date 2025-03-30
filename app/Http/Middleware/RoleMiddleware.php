<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $user = Auth::user();

        if (!$user || !($user instanceof User) || !method_exists($user, 'hasRole') || !$user->hasRole($role)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
