<?php

namespace App\Http\Controllers;
use App\Models\VerifyAdmin;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;

class VerifyAdminController extends Controller
{
    public function verifyAdm(Request $request): RedirectResponse
    {
        $hash = $request->route('hash');
        $userForVerify = VerifyAdmin::where('hash', $hash)->first();
        if(!$userForVerify){
            return redirect()->route('login')->withErrors( ['email' => 'Verificação inválida']);
        }

        $userForVerify->update(['user_verified_at' => now()]);
        //alterar rota para uma tela de succeso.
        $userForEmail = User::where('id',$userForVerify->user_id)->first();
        $userForEmail->notify(new \App\Notifications\UserVerifiedNotification());
        return redirect()->route('verify.return', ['message' => 'O cadastro do usuário foi aprovado.']);
    }
}
