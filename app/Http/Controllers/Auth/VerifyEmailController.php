<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use App\Models\User;

class VerifyEmailController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $userId = $request->route('id');
        $hash = $request->route('hash');
        $user = User::find($userId);

        if (!$user) {
            return redirect()->route('login')->withErrors(['email' => 'Usuário não encontrado']);
        }

        if (!hash_equals((string) $hash, (string) sha1($user->getEmailForVerification()))) {
            return redirect()->route('login')->withErrors(['email' => 'Hash de verificação inválido']);
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(route('dashboard', absolute: false) . '?verified=1');
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));

            // Instanciando o modelo da tabela Verificacoes
            \App\Models\VerifyAdmin::createHash($user);


        }



        return redirect()->route('verify.return', ['message' => 'Seu email foi verificado, agora um de nossos administradores irá analisar seu perfil para aprovar seu cadastro.']);
    }
}
