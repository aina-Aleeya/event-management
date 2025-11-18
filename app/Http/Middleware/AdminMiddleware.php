<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (! $user || $user->role !== 'admin') {
            return redirect('/')->with('error', 'You are not authorised to access that page.');
        }
        return $next($request);
    }
}
