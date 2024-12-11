<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CheckUserApproval
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        /** @var User $user */

        $user = Auth::user();
        // Verifica se o usuário está logado
        if (!$user) {
            return redirect()->route('login');
        }

        // Verifica se o e-mail foi confirmado
        if (!$user->hasVerifiedEmail()) {
            Auth::logout();
            return redirect()->route('login')
                ->withErrors(['email' => 'Você precisa verificar seu e-mail antes de acessar sua conta.']);
        }
        if (config('config.config.aprovar_cadastro') === 'S') {
            // Verifica se o cadastro foi aprovado pelo administrador
            $verificacao = \App\Models\VerifyAdmin::where('user_id', $user->id)->first();

            if (is_null($verificacao) || is_null($verificacao->user_verified_at)) {
                Auth::logout();
                return redirect()->route('login')
                    ->withErrors(['email' => 'Seu cadastro ainda não foi aprovado pelo administrador.']);
            }
        }
        return $next($request);
    }
}
