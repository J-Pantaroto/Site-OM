<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Venda;
use App\Models\User;
use App\Models\ItensVenda;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
class VendaController extends Controller
{
    public function registrarVenda(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'produtos' => 'required|array|min:1',
            'produtos.*.id' => 'required|exists:produtos,id',
            'produtos.*.quantidade' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Dados inválidos',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $user = Auth::user();
            $produtosRequest = $request->produtos;

            $produtos = collect($produtosRequest)->map(function ($item) {
                $produto = Produto::findOrFail($item['id']);
                return [
                    'codigo' => (string) $produto->codigo,
                    'quantidade' => (int) $item['quantidade'],
                    'grupo' => $produto->grupo
                ];
            });

            DB::transaction(function () use ($user, $produtos) {
                $estadoCliente = strtoupper($user->estado?->sigla ?? 'MS');
                $cfop = ($estadoCliente === 'MS') ? '5102' : '6102';
                $grupo = $produtos->first()['grupo'] ?? '000000';
                $grupo = str_pad((string) $grupo, 6, '0', STR_PAD_LEFT);

                $payload = [
                    "identificacao_cliente" => preg_replace('/\D/', '', $user->cpf_cnpj),
                    "nome_cliente" => $user->name,
                    "estado_cliente" => $estadoCliente,
                    "condicao" => "A Vista",
                    "cfop" => $cfop,
                    "vendedor" => "0001",
                    "grupo" => $grupo,
                    "produtos" => $produtos->map(function ($item) {
                        return [
                            "codigo" => str_pad($item['codigo'], 9, '0', STR_PAD_LEFT),
                            "quantidade" => $item['quantidade'],
                        ];
                    })->values()->toArray()
                ];

                Log::info("Payload JSON manual:\n" . json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

                $response = Http::withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ])->post('http://192.168.1.50:22288/api/sales/vendas', $payload);

                Log::info('Resposta da API:', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                $responseData = $response->json();

                if (!$response->successful() || empty($responseData['status'])) {
                    Log::error('Erro na resposta da API externa de vendas', [
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);
                    throw new \Exception('Erro ao registrar venda na API externa');
                }

                $orderId = $responseData['orderId'] ?? null;

                if (!$orderId) {
                    throw new \Exception('ID do pedido ausente na resposta da API');
                }

                $dataVenda = now()->format('d/m/Y');
                // Persist
                $total = null;
                if (config('config.config.exibir_preco') === 'S') {
                    $total = $produtos->sum(function($item){
                        $codigoFormatado = str_pad($item['codigo'],9,'0', STR_PAD_LEFT);
                        $produto = Produto::where('codigo', $codigoFormatado)->first();
                        return $produto->preco * $item['quantidade'];
                    });
             
                }
                    $venda = Venda::create([
                        'cliente_id' => $user->id,
                        'data_venda' => $dataVenda,
                        'codigo' => str_pad($orderId, 7, '0', STR_PAD_LEFT),
                        'A_SITU' => 'pendente',
                        'total' => $total
                    ]);
                
                foreach ($produtos as $item) {
                    $codigoFormatado = str_pad($item['codigo'], 9, '0', STR_PAD_LEFT);

                    Log::info("Buscando produto com código: {$codigoFormatado}");
                    $produtoModel = Produto::where('codigo', $codigoFormatado)->first();
                    if (!$produtoModel) {
                        Log::error("Produto não encontrado para o código: {$codigoFormatado}");
                        throw new \Exception("Produto com código {$codigoFormatado} não encontrado.");
                    }
                    if (config('config.config.exibir_preco') === 'S') {
                        ItensVenda::create([
                            'venda_id' => $venda->id,
                            'produto_id' => $produtoModel->id,
                            'quantidade' => $item['quantidade'],
                            'preco' => $produtoModel->preco,
                            'subtotal' => $produtoModel->preco * $item['quantidade']
                        ]);
                    } else {
                        ItensVenda::create([
                            'venda_id' => $venda->id,
                            'produto_id' => $produtoModel->id,
                            'quantidade' => $item['quantidade'],
                        ]);
                    }
                }
            });


            return response()->json([
                'status' => 'success',
                'message' => 'Venda registrada com sucesso!'
            ], 201);

        } catch (\Exception $e) {
            Log::error('Erro ao registrar venda: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erro interno ao registrar a venda.'
            ], 500);
        }
    }

    public function listarComprasCliente()
    {
        $user = Auth::user();
        /** @var User $user */

        // Sincronizar status das vendas do cliente
        $this->sincronizarStatusDoCliente($user);

        $showAddressWarning = !$user->isAddressComplete();
        $exibirPreco = config('config.exibir_preco') === 'S';

        $vendas = Venda::with(['itensVenda.produto'])
            ->where('cliente_id', $user->id)
            ->get()
            ->groupBy(function ($venda) {
                return in_array($venda->A_SITU, ['pendente', 'liberado', 'em separacao']) ? 'em_andamento' : 'finalizado';
            })
            ->map(function ($group, $status) use ($exibirPreco) {
                return $group->map(function ($venda) use ($exibirPreco) {
                    return [
                        'id' => $venda->id,
                        'data_venda' => $venda->data_venda,
                        'status' => $venda->A_SITU,
                        'total' => $exibirPreco ? $venda->total : null,
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

    private function sincronizarStatusDoCliente($user)
    {
        $vendas = Venda::where('cliente_id', $user->id)
            ->whereNotNull('codigo')
            ->get();

        foreach ($vendas as $venda) {
            try {
                $response = Http::acceptJson()
                    ->get("http://192.168.1.50:22288/api/sales/vendas/status/{$venda->codigo}");

                if ($response->ok()) {
                    $data = $response->json();
                    $venda->A_SITU = $data['status'] ?? $venda->A_SITU;
                    $venda->save();
                }
            } catch (\Exception $e) {
                \Log::error("Erro ao atualizar status da venda {$venda->id}: " . $e->getMessage());
            }
        }
    }
}
