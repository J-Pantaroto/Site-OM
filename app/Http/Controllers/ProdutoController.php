<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Grupo;
use Illuminate\Http\Request;
use App\Models\ImagemProduto;
use Illuminate\Support\Facades\Storage;

class ProdutoController extends Controller
{

    public function index()
    {
        $totalProdutos = 20;
        $produtos = Produto::orderBy('nome', 'ASC')->paginate($totalProdutos);
        $grupos = Grupo::all();

        return view('produtos', compact('grupos', 'produtos'));
    }

    public function pesquisaProduto($produto)
    {
        $prod = Produto::where('nome', 'like', "%{$produto}%")->first();
        return view('produto', compact('prod'));
    }

    public function pesquisarProdutos(Request $request)
    {
        $pesquisa = $request->input('pesquisa');
        if ($pesquisa === '' || $pesquisa === null) {
            $produtos = Produto::orderBy('nome', 'ASC')->paginate(20);
        } else {
            $produtos = Produto::where('nome', 'like', "%{$pesquisa}%")->paginate(20);
        }

        return response()->json([
            'status' => 'sucesso',
            'quantidade' => $produtos->count(),
            'produtos' => $produtos->items(),
            'links' => $produtos->links()->render()
        ]);
    }
    public function create()
    {
        return view('produtos.create');
    }

    public function store(Request $request) {}
    public function show(string $id) {}
    public function edit($id)
    {
        $produto = Produto::with('imagens')->findOrFail($id);
        return view('produto.editproduto', compact('produto'));
    }

    public function update(Request $request, $id)
    {
        $produto = Produto::findOrFail($id);

        $request->validate([
            'nome' => 'required',
            'descricao' => 'required',
            'imagens.*' => 'image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);
        if ($request->hasFile('imagens')) {
            foreach ($request->file('imagens') as $imagem) {
                $imagemPath = $imagem->store('produtos', 'public'); // Guarda a imagem na pasta 'produtos'
                $produto->imagens()->create([
                    'imagem' => $imagemPath,
                ]);
            }
        }
        $produto->update($request->only(['nome', 'descricao']));

        $imagemSelecionada = $request->input('imagem_principal');
        
        if ($imagemSelecionada) {
            $produto->imagem = $imagemSelecionada;
        } elseif (empty($produto->imagem)) {
            $primeiraImagem = ImagemProduto::where('produto_id', $id)->first();
            if ($primeiraImagem) {
                $produto->imagem = $primeiraImagem->imagem;
            }
        }
    
        $produto->save();

        return redirect()->route('produtos')->with('success', 'Produto atualizado com sucesso.');
    }

    public function destroy($id)
    {
        $produto = Produto::findOrFail($id); // Encontra o produto pelo ID
        $produto->delete(); // Exclui o produto
        return redirect()->route('produtos')->with('success', 'Produto excluído com sucesso.');
    }

    public function destroyImagem($id)
    {
        $imagem = ImagemProduto::findOrFail($id);
        $produtoId = $imagem->produto_id;
        // Verifica se o arquivo existe e o deleta
        if (Storage::disk('public')->exists($imagem->imagem)) {
            Storage::disk('public')->delete($imagem->imagem);
        }
        // Remove o registro do banco de dados
        $imagem->delete();
        return redirect()->route('produtos.edit', $produtoId)->with('success', 'Imagem removida com sucesso.');
    }
}
