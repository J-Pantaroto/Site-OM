<?php
namespace App\Http\Controllers;
use App\Models\Produto;
use App\Models\Grupo;
use Illuminate\Http\Request;
class ProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
        if($pesquisa===''){
            $produtos = Produto::orderBy('nome', 'ASC')->paginate(20);
        }else{
            $produtos = Produto::where('nome', 'like', "%{$pesquisa}%")->paginate(20);
        }
        
        return response()->json([
            'status' => 'sucesso',
            'quantidade' => $produtos->count(),
            'produtos' => $produtos->items(),
            'links' => $produtos->links()->render()
        ]);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('produtos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $produto = Produto::findOrFail($id);
        return view('produto.editproduto', compact('produto'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $produto = Produto::findOrFail($id);

        // Validação (opcional)
        $request->validate([
            'nome' => 'required',
            // Outros campos que você quiser validar
        ]);

        // Atualiza os dados do produto
        $produto->update($request->all());
        return redirect()->route('produtos.edit')->with('success', 'Produto atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $produto = Produto::findOrFail($id); // Encontra o produto pelo ID
        $produto->delete(); // Exclui o produto
        return redirect()->route('produtos')->with('success', 'Produto excluído com sucesso.');
    }

}
