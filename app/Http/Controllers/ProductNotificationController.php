<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductNotification;
use App\Models\Produto;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProdutoDisponivel;

class ProductNotificationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'produto_id' => 'required|exists:produtos,id',
            'email' => 'required|email',
        ]);
        $produto_id = $request->produto_id;
        $email = $request->email;
        if (ProductNotification::where('produto_id', $produto_id)
                ->where('email', $email)
                ->exists()) {
            return response()->json([
                'mensagem' => 'Você já está cadastrado para receber notificações deste produto.'
            ]);
        }
        ProductNotification::create($request->only('produto_id', 'email'));

        return response()->json(['status' => 'sucesso', 'mensagem' => 'Você será avisado quando o produto estiver disponível.']);
    }

    public function notifyUsers($produto_id)
    {
        $produto = Produto::findOrFail($produto_id);
        $notificacoes = ProductNotification::where('produto_id', $produto_id)->get();

        foreach ($notificacoes as $notificacao) {
            Mail::to($notificacao->email)->send(new ProdutoDisponivel($produto));
        }

        ProductNotification::where('produto_id', $produto_id)->delete();
    }
}
