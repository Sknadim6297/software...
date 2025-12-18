<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckBDMStatus
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if user has BDM profile
            if ($user->bdm) {
                // If BDM is terminated or login disabled, logout immediately
                if ($user->bdm->isTerminated() || !$user->bdm->can_login) {
                    Auth::logout();
                    return redirect()->route('login')
                        ->with('error', 'Your account has been terminated. Access denied.');
                }
            }
        }
        
        return $next($request);
    }
}
