<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CarrinhoController extends Controller
{
    function atualizarCarrinho(Request $request)
    {
        $produtos = $request->input('produtos');
        $produtosSerializados = json_encode($produtos);
        setcookie('carrinho', $produtosSerializados, time() + 86400, '/');
        return response()->json(['status' => 'sucesso']);
    }

    function carregarCarrinho()
    {
        if (isset($_COOKIE['carrinho'])) {
            return json_decode($_COOKIE['carrinho'], true);
        } else {
            return [];
        }
    }

    function limparCarrinho()
    {
        setcookie('carrinho', '', time() - 3600, '/');
        return response()->json(['status' => 'sucesso']);
    }
}