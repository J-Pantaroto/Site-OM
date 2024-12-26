<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Grupo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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
        $nenhumProdutoComPreco = $exibirPreco && $produtos->isEmpty();
        $isAdmin = Auth::check() && Auth::user()->is_admin || $isAdmin = Auth::check() && Auth::user()->isSuperVisor();
        $mostrarBotaoVerMais = $produtos->total() > $produtos->perPage();
        return view('home', compact('grupos', 'produtos', 'nenhumProdutoComPreco', 'isAdmin','mostrarBotaoVerMais'));
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
