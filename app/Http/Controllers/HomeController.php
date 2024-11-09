<?php

namespace App\Http\Controllers;
use App\Models\Produto;
use App\Models\Grupo;
use Illuminate\Http\Request;

class HomeController extends Controller
{
  public function index()
    {
        $produtos = Produto::paginate(12);
        $grupos = Grupo::all();
        return view('home', compact('grupos', 'produtos'));
    }


    public function buscarProduto(Request $request)
    {
        $pesquisa = $request->input('pesquisa');
        $categoria = $request->input('categoria');
        $limite = $request->input('limite');
        $offset = $request->input('offset');
        $escopo = $request->input('escopo');
        
        $query = Produto::query();
        
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
    public function adicionarProdutoCarrinho(Request $request){
        $id = $request->input('produto_id');
        $nome = $request->input('nome');
    }
    public function create()
    {
    }

   
    public function store(Request $request)
    {
    }

 
    public function show(string $id)
    {
    }

    public function edit(string $id)
    {
    }

    public function update(Request $request, string $id)
    {
    }


    public function destroy(string $id)
    {
    }
}
