<?php

namespace App\Http\Controllers\Auth;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    public function store(Request $request): RedirectResponse
    {

        $dados = $request->all();
        $user = User::where('email', $dados['emailValor'])->first();
        if ($user && $user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false));
        } else if ($user) {
            $user->sendEmailVerificationNotification();
            return back()->with('status', 'Sucesso');
        }

        return back()->with('email', 'Usuário não encontrado');
    }

}
