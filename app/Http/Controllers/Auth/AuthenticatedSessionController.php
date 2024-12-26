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
        if (!Auth::user()->hasVerifiedEmail()) {
            session(['email' => Auth::user()->email]);
            Auth::logout();
            return redirect()->route('verification.notice')->with('message', 'Por favor, verifique seu e-mail antes de continuar.');
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
