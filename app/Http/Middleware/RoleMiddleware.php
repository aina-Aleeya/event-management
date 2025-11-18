<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    // public function handle(Request $request, Closure $next, $role)
    // {
    //     if (!auth()->check()) {
    //         return redirect('/login');
    //     }

    //     $user = auth()->user();

    //     if ($user->role !== $role) {
    //         if ($user->role === 'admin') {
    //             return redirect('/admin/dashboard')->with('message', 'You are not allowed to access that page.');
    //         }

    //         if ($user->role === 'user') {
    //             return redirect('/dashboard')->with('message', 'You are not allowed to access that page.');
    //         }

    //         abort(403, 'Unauthorized'); // fallback
    //     }

    //     return $next($request);
    // }
}
