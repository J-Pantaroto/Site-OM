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
    function removerProdutoCookie(Request $request)
    {
        $nomeProduto = $request->input('nome');
    
        // Recupera o cookie do carrinho
        $carrinho = isset($_COOKIE['carrinho']) ? json_decode($_COOKIE['carrinho'], true) : [];
    
        if (!empty($carrinho)) {
            // Filtra os produtos, removendo o produto que corresponde ao nome informado
            $carrinho = array_filter($carrinho, function($produto) use ($nomeProduto) {
                return $produto['nome'] !== $nomeProduto; //Aqui retorna apenas os produtos q tiverem um nome diferente do q eu setei la em cima
            });
        }
    
        // Atualiza o cookie do carrinho sem o produto removido
        setcookie('carrinho', json_encode($carrinho), time() + (86400 * 30), '/'); // Cookie atualizado por 30 dias
    
        // Retorna uma resposta de sucesso
        return response()->json(['status' => 'sucesso']);
    }
    
    function limparCarrinho()
    {
        setcookie('carrinho', '', time() - 3600, '/');
        return response()->json(['status' => 'sucesso']);
    }
}