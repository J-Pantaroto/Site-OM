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
        $prod = Produto::where('slug', 'like', "%{$produto}%")->first();
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
            'produtos' => $produtos->map(function ($produto) {
                return [
                    'id' => $produto->id,
                    'imagem' => $produto->imagem,
                    'nome' => $produto->nome,
                    'preco' => $produto->preco ?? '',
                    'quantidade' => $produto->quantidade ?? '',
                ];
            }),
            'links' => $produtos->links()->render()
        ]);
    }
    public function create()
    {
        return view('produtos.create');
    }
    public function edit($id)
    {
        $produto = Produto::with(['imagens' => function ($query) {
            $query->where('imagem', '!=', 'produtos/placeholder.png');
        }])->findOrFail($id);

        return view('produto.editproduto', compact('produto'));
    }

    public function update(Request $request, $id)
    {
        $produto = Produto::findOrFail($id);

        $request->validate([
            'imagens.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'nome' => 'required|string|max:255',
            'descricao' => 'required|string',
            'preco' => 'nullable|string|',
        ]);

        $precoFormatado = $request->input('preco')
            ? (float) str_replace(',', '.', $request->input('preco'))
            : null;
        // Se o produto está com o placeholder como imagem principal, remove-o
        if ($produto->imagem === 'produtos/placeholder.png') {
            $produto->imagens()->where('imagem', 'produtos/placeholder.png')->delete();
        }

        $novaImagemPrincipal = null;

        if ($request->hasFile('imagens')) {
            foreach ($request->file('imagens') as $imagem) {
                $path = $imagem->store('produtos', 'public');
                $novaImagem = $produto->imagens()->create(['imagem' => $path]);

                // Defina a primeira imagem carregada como principal se não houver outra principal
                if (!$produto->imagens()->where('principal', true)->exists() && !$novaImagemPrincipal) {
                    $novaImagemPrincipal = $novaImagem->imagem;
                    $novaImagem->update(['principal' => true]);
                    $produto->imagem = $novaImagemPrincipal; // Atualiza o campo `imagem` do produto diretamente
                }
            }
        }

        // Atualizar nome, descrição e imagem do produto, garantindo que a imagem principal seja atualizada
        $produto->nome = $request->input('nome');
        $produto->descricao = $request->input('descricao');
        $produto->preco = $precoFormatado;
        $produto->save();

        // Caso o usuário selecione manualmente uma imagem como principal
        if ($request->filled('imagem_principal')) {
            $produto->imagens()->update(['principal' => false]);
            $imagemPrincipal = $produto->imagens()->where('imagem', $request->input('imagem_principal'))->first();
            if ($imagemPrincipal) {
                $imagemPrincipal->update(['principal' => true]);
                $produto->imagem = $imagemPrincipal->imagem;
                $produto->save();
            }
        }

        // Se ainda não houver imagem principal após o upload, definir o placeholder como imagem padrão
        if (!$produto->imagens()->where('principal', true)->exists()) {
            $produto->imagem = 'produtos/placeholder.png';
            $produto->save();
        }

        return redirect()->route('produtos')->with('success', 'Produto atualizado com sucesso!');
    }

    public function destroy($id)
    {
        $produto = Produto::findOrFail($id);
        $produto->delete();
        return redirect()->route('produtos')->with('success', 'Produto excluído com sucesso.');
    }

    public function destroyImagem($id)
    {
        $imagem = ImagemProduto::findOrFail($id);
        $produtoId = $imagem->produto_id;

        if (Storage::disk('public')->exists($imagem->imagem)) {
            Storage::disk('public')->delete($imagem->imagem);
        }

        $imagem->delete();

        if ($imagem->principal) {
            $novaImagemPrincipal = ImagemProduto::where('produto_id', $produtoId)->first();

            if ($novaImagemPrincipal) {
                $novaImagemPrincipal->update(['principal' => true]);
                Produto::where('id', $produtoId)->update(['imagem' => $novaImagemPrincipal->imagem]);
            } else {
                Produto::where('id', $produtoId)->update(['imagem' => 'produtos/placeholder.png']);
            }
        }

        return redirect()->route('produtos.edit', $produtoId)->with('success', 'Imagem removida com sucesso.');
    }
}
