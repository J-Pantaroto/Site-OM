<?php
namespace App\Http\Controllers;

use App\Models\Venda;
use App\Models\ItensVenda;
use Illuminate\Http\Request;
class VendaController extends Controller
{
    public function registrarVenda(Request $request)
    {
        //validacoes
        $request->validate([
            'produtos' => 'required|array',
            'produtos.*.id' => 'required|exists:produtos,id',
            'produtos.*.quantidade' => 'required|integer|min:1',
        ]);

        // Verificar se o usuário está autenticado
        $user = auth()->user();

        if ($user) {
            // Criar a venda com cliente_id e data atual
            $venda = Venda::create([
                'cliente_id' => $user->id,
                'data_venda' => now(),  // Data da venda (created_at será preenchido automaticamente)
            ]);

            // Inserir os itens da venda
            foreach ($request->produtos as $produto) {
                ItensVenda::create([
                    'venda_id' => $venda->id,
                    'produto_id' => $produto['id'], // Supondo que o array de produtos tenha um campo 'id'
                    'quantidade' => $produto['quantidade'], // Supondo um campo 'quantidade'
                ]);
            }

            return response()->json(['message' => 'Venda registrada com sucesso!', 'venda_id' => $venda->id]);
        }

        return response()->json(['message' => 'Usuário não autenticado'], 401);
    }
    public function listarComprasCliente()
    {
        $user = auth()->user();
        // Obtém todas as vendas do cliente
        $vendas = Venda::with('itensVenda.produto') // Carrega os itens da venda e o produto relacionado
            ->where('cliente_id', $user->id)
            ->get();

        return view('dashboard', ['vendas' => $vendas]);
    }

}
