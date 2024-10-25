<?php

namespace App\Http\Controllers\Auth;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Session;
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
            Session::forget('email');
            return redirect()->intended(route('dashboard', absolute: false));
        } else if ($user) {
            if (!session()->has('email')) {
                Session::put('email', $user->email);
            }
            $user->sendEmailVerificationNotification();
            return back()->with('status', 'Sucesso');
        }

        return back()->with('email', 'Usuário não encontrado');
    }

}
