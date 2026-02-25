<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    /**
     * Redirect after registration. Uses intended URL when set (e.g. event dashboard after accepting invitation).
     */
    public function toResponse($request)
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        }
        if ($user->role === 'organiser') {
            return redirect()->intended(route('organiser.dashboard'));
        }

        return redirect()->intended(route('dashboard'));
    }
}
