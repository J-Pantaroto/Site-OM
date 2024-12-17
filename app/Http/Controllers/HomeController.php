<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Grupo;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $exibirPreco = config('config.config.exibir_preco') === 'S';
        $produtosQuery = Produto::query();
        if ($exibirPreco) {
            $produtosQuery->whereNotNull('preco')->where('preco', '>', 0);
        }
        $produtos = $produtosQuery->paginate(12);

        $grupos = Grupo::whereHas('produtos', function ($query) use ($exibirPreco) {
            if ($exibirPreco) {
                $query->whereNotNull('preco')->where('preco', '>', 0);
            }
        })->get();

        return view('home', compact('grupos', 'produtos'));
    }


    public function buscarProduto(Request $request)
    {
        $exibirPreco = config('config.config.exibir_preco') === 'S';
        $pesquisa = $request->input('pesquisa');
        $categoria = $request->input('categoria');
        $limite = $request->input('limite');
        $offset = $request->input('offset');
        $escopo = $request->input('escopo');

        $query = Produto::query();
        if ($exibirPreco) {
            $query->whereNotNull('preco')->where('preco', '>', 0);
        }

        if ($escopo != "todos") {
            if ($pesquisa) {
                $query->where('nome', 'like', '%' . $pesquisa . '%');
            }
            if ($categoria) {
                $query->where('grupo_id', $categoria);
            }
        }

        $totalProdutos = $query->count();

        $produtos = $query->offset($offset)
            ->limit($limite)
            ->get();

        $produtos->transform(function ($produto) {
            $produto->imagem = asset('storage/' . $produto->imagem);
            return $produto;
        });

        return response()->json([
            'status' => 'sucesso',
            'quantidade' => $produtos->count(),
            'totalProdutos' => $totalProdutos,
            'produtos' => $produtos
        ]);
    }
    public function adicionarProdutoCarrinho(Request $request)
    {
        $id = $request->input('produto_id');
        $nome = $request->input('nome');
        $preco = $request->input('preco');
    }
}
