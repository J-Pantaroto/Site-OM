<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Venda;
use App\Models\ItensVenda;
use Illuminate\Http\Request;
class VendaController extends Controller
{
    public function registrarVenda(Request $request)
    {
        try {
            // Validações
            $request->validate([
                'produtos' => 'required|array',
                'produtos.*.id' => 'required|exists:produtos,id',
                'produtos.*.quantidade' => 'required|integer|min:1',
            ]);

            $produtos = $request->input('produtos');

            // Verificar se o usuário está autenticado
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Usuário não autenticado.',
                ], 401);
            }

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

            return response()->json([
                'status' => 'success',
                'message' => 'Venda registrada com sucesso!',
                'venda_id' => $venda->id,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao registrar a venda: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function listarComprasCliente()
    {
        $user = Auth::user();
        // Obtém todas as vendas do cliente
        $vendas = Venda::with('itensVenda.produto') // Carrega os itens da venda e o produto relacionado
            ->where('cliente_id', $user->id)
            ->get();

        return view('dashboard', ['vendas' => $vendas]);
    }

}
