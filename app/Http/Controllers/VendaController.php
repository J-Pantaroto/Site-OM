<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Venda;
use App\Models\User;
use App\Models\ItensVenda;
use App\Models\Produto;
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
                'produtos.*.preco' => 'nullable|numeric|min:0',
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
            $exibirPreco = config('config.config.exibir_preco') === 'S';
            $validarEstoque = config('config.config.validar_estoque') === 'S';

            $total = $exibirPreco ? array_reduce($produtos, function ($carry, $produto) {
                return $carry + ($produto['preco'] * $produto['quantidade']);
            }, 0) : 0;
            foreach ($produtos as $produtoData) {
                $produto = Produto::find($produtoData['id']);

                if (!$produto) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "O produto ID {$produtoData['id']} não foi encontrado.",
                    ], 400);
                }

                if ($validarEstoque && $produto->quantidade < $produtoData['quantidade']) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "Estoque insuficiente para o produto: {$produto->nome}",
                    ], 400);
                }
            }
            $venda = Venda::create([
                'cliente_id' => $user->id,
                'data_venda' => now(),
                'total' => $total ?? 0,
            ]);

            foreach ($produtos as $produtoData) {
                $produto = Produto::find($produtoData['id']);
                ItensVenda::create([
                    'venda_id' => $venda->id,
                    'produto_id' => $produto->id,
                    'quantidade' => $produtoData['quantidade'],
                    'preco' => $exibirPreco ? $produtoData['preco'] : 0,
                    'subtotal' => $exibirPreco ? ($produtoData['preco'] * $produtoData['quantidade']) : 0,
                ]);

                if ($validarEstoque) {
                    $produto->decrement('quantidade', $produtoData['quantidade']);
                }
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
        /** @var User $user */
        $showAddressWarning = !$user->isAddressComplete();
        $exibirPreco = config('config.exibir_preco') === 'S';

        $vendas = Venda::with(['itensVenda.produto'])
            ->where('cliente_id', $user->id)
            ->get()
            ->groupBy(function ($venda) {
                return in_array($venda->situacao, ['pendente', 'liberado', 'em separacao']) ? 'em_andamento' : 'finalizado';
            })
            ->map(function ($group, $status) use ($exibirPreco) {
                return $group->map(function ($venda) use ($exibirPreco) {
                    $total = $exibirPreco ? $venda->itensVenda->sum(function ($item) {
                        return $item->preco * $item->quantidade;
                    }) : null;

                    return [
                        'id' => $venda->id,
                        'data_venda' => $venda->data_venda,
                        'status' => $venda->situacao,
                        'total' => $total,
                        'itens' => $venda->itensVenda->map(function ($item) use ($exibirPreco) {
                            return [
                                'nome' => $item->produto->nome,
                                'quantidade' => $item->quantidade,
                                'preco' => $exibirPreco ? $item->preco : null,
                                'subtotal' => $exibirPreco ? $item->preco * $item->quantidade : null,
                            ];
                        }),
                    ];
                });
            });

        return view('dashboard', [
            'pedidosEmAndamento' => $vendas->get('em_andamento', collect()),
            'ultimasCompras' => $vendas->get('finalizado', collect())->take(5),
            'showAddressWarning' => $showAddressWarning,
            'exibirPreco' => $exibirPreco
        ]);
    }
}
