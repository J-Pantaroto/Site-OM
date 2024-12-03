<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAddressComplete
{
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && (empty($user->address) || empty($user->city) || empty($user->state) || empty($user->zip_code))) {
           
                return response()->json([
                    'status' => 'error',
                    'message' => 'Você precisa completar seu endereço antes de finalizar a compra.',
                    'redirect_url' => route('profile.edit', ['incomplete' => true]),
                ], 403);
            
        }

        return $next($request);
    }
}
