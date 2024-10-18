<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // Autentica o usuário
        $request->authenticate();

        // Agora podemos acessar o usuário autenticado
        $user = Auth::user();

        // Verifica se o e-mail do usuário foi verificado
        if (!$user->hasVerifiedEmail()) {
            Auth::logout();

            // Redireciona para a página de login com uma mensagem de erro e o email
            return redirect()->route('login')
                ->withErrors([
                    'email' => 'Você precisa verificar seu e-mail antes de acessar a conta. O link de verificação foi enviado para ' . $user->email . '.',
                ])
                ->with('resentLink', true)
                ->with('email', $user->email); // Adiciona o email à sessão
        }

        // Regenera a sessão e define o cookie do carrinho
        $request->session()->regenerate();
        setcookie('carrinho', json_encode([]), time() + 86400, '/');

        // Redireciona para a home ou rota desejada
        return redirect()->intended(route('home'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        setcookie('carrinho', '', time() - 3600, '/');

        return redirect('/');
    }
}
