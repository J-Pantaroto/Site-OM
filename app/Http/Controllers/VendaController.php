<?php
namespace App\Http\Controllers;

use App\Models\Venda;
use App\Models\ItensVenda;
use Illuminate\Http\Request;
class VendaController extends Controller
{
    public function registrarVenda(Request $request)
    {
        try {
            //validações
            $request->validate([
                'produtos' => 'required|array',
                'produtos.*.id' => 'required|exists:produtos,id',
                'produtos.*.quantidade' => 'required|integer|min:1',
            ]);
            $produtos = $request->input('produtos');
    
            // Verificar se o usuário está autenticado  
            $user = auth()->user();
    
            if ($user) {
                // Criar a venda com cliente_id e data atual
                $venda = Venda::create([
                    'cliente_id' => $user->id,
                    'data_venda' => now(),
                ]);
    
                // Inserir os itens da venda
                foreach ($produtos as $produto) {
                    ItensVenda::create([
                        'venda_id' => $venda->id,
                        'produto_id' => $produto['id'],
                        'quantidade' => $produto['quantidade'],
                    ]);
                }
    
                return response()->json(['message' => 'Venda registrada com sucesso!', 'venda_id' => $venda->id]);
            }
    
            return response()->json(['message' => 'Usuário não autenticado'], 401);
        } catch (\Exception $e) {
            // Em caso de erro, retornar a mensagem de erro como JSON
            return response()->json(['error' => $e->getMessage()], 500);
        }
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
