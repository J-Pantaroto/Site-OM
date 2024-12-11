<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsSupervisor
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user || !$user->isSupervisor()) {
            return redirect()->route('home')->withErrors(['permission' => 'Você não tem permissão para acessar esta página.']);
        }

        return $next($request);
    }
}
