<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class CarrinhoController extends Controller
{
    function atualizarCarrinho(Request $request)
    {
        $request->validate([
            'produtos' => 'required|array|min:1',
            'produtos.*.nome' => 'required|string|max:255',
            'produtos.*.imagem' => 'required|string',
            'produtos.*.quantidade' => 'required|integer|min:1',
        ]);
        $produtos = $request->input('produtos');
        $produtosComId = [];

        foreach ($produtos as $produto) {
            $produtoExistente = Produto::where('nome', $produto['nome'])->first();
            if ($produtoExistente) {
                if (config('config.config.validar_estoque') === 'S' && $produtoExistente->quantidade < $produto['quantidade']) {
                    return response()->json([
                        'status' => 'erro',
                        'mensagem' => "Estoque insuficiente para o produto: {$produtoExistente->nome}",
                    ], 400);
                }
                $produtosComId[] = [
                    'id' => $produtoExistente->id,
                    'nome' => $produto['nome'],
                    'imagem' => $produto['imagem'],
                    'preco' => config('config.config.exibir_preco') === 'S' ? $produtoExistente->preco : null,
                    'quantidade' => $produto['quantidade']
                ];
            }
        }

        $produtosSerializados = json_encode($produtosComId);
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
            $carrinho = array_filter($carrinho, function ($produto) use ($nomeProduto) {
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
