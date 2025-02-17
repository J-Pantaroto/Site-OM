<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Produto;
use App\Models\Grupo;
use App\Models\SubGrupo;
use Illuminate\Support\Str;
use App\Http\Controllers\ProductNotificationController;

class Sincronizar implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */

    public function atualizarGrupos()
    {
        $currentPage = 1;
        do {
            $response = Http::get('http://192.168.1.50:22288/api/sales/grupos?', [
                'quantidadeMaximaRegistros' => 500,
                'page' => $currentPage,
            ]);

            if ($response->ok()) {
                $data = $response->json();
                $grupos = $data['data'] ?? [];
                $nextPageUrl = $data['next_page_url'];

                foreach ($grupos as $grupo) {
                    Grupo::updateOrCreate(
                        ['codigo' => $grupo['codigo']],
                        [
                            'codigo' => $grupo['codigo'],
                            'descricao' => $grupo['descricao'],
                        ]
                    );
                }
                $currentPage++;
            } else {
                Log::error('Erro ao sincronizar grupos: ' . $response->body());
                break;
            }
        } while ($nextPageUrl);
    }

    public function atualizarSubGrupos()
    {
        $currentPage = 1;
        do {
            $response = Http::get('http://192.168.1.50:22288/api/sales/subgrupos?', [
                'quantidadeMaximaRegistros' => 500,
                'page' => $currentPage,
            ]);

            if ($response->ok()) {
                $data = $response->json();
                $subgrupos = $data['data'] ?? [];
                $nextPageUrl = $data['next_page_url'];

                foreach ($subgrupos as $subgrupo) {
                    Subgrupo::updateOrCreate(
                        ['codigo' => $subgrupo['codigo']],
                        [
                            'codigo' => $subgrupo['codigo'],
                            'descricao' => $subgrupo['descricao'],
                        ]
                    );
                }
                $currentPage++;
            } else {
                Log::error('Erro ao sincronizar SubGrupos: ' . $response->body());
                break;
            }
        } while ($nextPageUrl);
    }
    public function atualizarProdutos()
    {
        $somenteCadastrar = config('config.config.somente_cadastrar_ao_atualizar') === 'S';
        $camposParaAtualizar = config('config.config.dados_produtos_para_sincronizar', 'todos');
        $permitidos = $camposParaAtualizar === 'todos' ? ['todos'] : explode(',', str_replace(' ', '', $camposParaAtualizar));
        $camposObrigatorios = ['inativo', 'quantidade', 'grupo', 'subgrupo'];
    
        $currentPage = 1;
    
        do {
            $response = Http::get('http://192.168.1.50:22288/api/sales/produtos', [
                'quantidadeMaximaRegistros' => 500,
                'page' => $currentPage,
            ]);
    
            if ($response->ok()) {
                $data = $response->json();
                $produtos = $data['data'] ?? [];
                $nextPageUrl = $data['next_page_url'];
    
                foreach ($produtos as $produto) {
                    try {
                        if ($produto['inativo'] === 'S') {
                            continue;
                        }
    
                        $produtoExistente = Produto::where('codigo', $produto['codigo'])->first();
                        $estoqueAnterior = $produtoExistente ? $produtoExistente->quantidade : 0;
                        // Se `somenteCadastrar` estiver ativado, pula atualização de produtos existentes
                        if ($somenteCadastrar && $produtoExistente) {
                            continue;
                        }
    
                        // Dados básicos do produto
                        $nomeProduto = $produto['descricao'] ?? 'Produto Sem Nome';
                        $descricaoProduto = $produto['descricao'] ?? 'Produto Sem Descrição';
                        $dadosProduto = $produto['dados'] ?? [];
                        $quantidade = $dadosProduto['quantidade'] ?? 0;
                        $precoVenda = $dadosProduto['precoVenda'] ?? 0;
                        $imagem = $produto['A_IMAG'] ?? 'produtos/placeholder.png';
                        $slugBase = Str::slug($nomeProduto);
                        $slug = $slugBase;
                        $contador = 1;
    
                        // Se o produto já existir, verifica quais campos podem ser atualizados
                        if ($produtoExistente) {
                            $podeAtualizar = false;
    
                            if (in_array('todos', $permitidos) || $produtoExistente->verificarDadosParaAtualizar('nome')) {
                                $podeAtualizar = true;
                            } else {
                                $nomeProduto = $produtoExistente->nome;
                            }
    
                            if (in_array('todos', $permitidos) || $produtoExistente->verificarDadosParaAtualizar('descricao')) {
                                $podeAtualizar = true;
                            } else {
                                $descricaoProduto = $produtoExistente->descricao;
                            }
    
                            if (in_array('todos', $permitidos) || $produtoExistente->verificarDadosParaAtualizar('preco')) {
                                $podeAtualizar = true;
                            } else {
                                $precoVenda = $produtoExistente->preco;
                            }
    
                            if (in_array('todos', $permitidos) || $produtoExistente->verificarDadosParaAtualizar('imagem')) {
                                $podeAtualizar = true;
                            } else {
                                $imagem = $produtoExistente->imagem;
                            }
    
                            // 🔹 Mantém o slug se o nome não for atualizado
                            if ($produtoExistente->nome !== $nomeProduto) {
                                while (Produto::where('slug', $slug)->exists()) {
                                    $slug = "{$slugBase}-{$contador}";
                                    $contador++;
                                }
                            } else {
                                $slug = $produtoExistente->slug;
                            }
    
                            // Se **nenhum campo** puder ser atualizado, ignoramos este produto
                            if (!$podeAtualizar) {
                                continue;
                            }
                        }
    
                        $produtoAtualizado = Produto::updateOrCreate(
                            ['codigo' => $produto['codigo']],
                            [
                                'nome' => $nomeProduto,
                                'descricao' => $descricaoProduto,
                                'slug' => $slug,
                                'inativo' => $produto['inativo'],
                                'grupo' => $produto['grupo'],
                                'subgrupo' => $produto['subgrupo'],
                                'imagem' => $imagem,
                                'quantidade' => $quantidade,
                                'preco' => $precoVenda
                            ]
                        );
                        if($estoqueAnterior <= 0 && $quantidade> 0 ){
                            app(ProductNotificationController::class)->notifyUsers($produtoAtualizado->id);
                        }
                    } catch (\Exception $e) {
                        Log::error("Erro ao sincronizar produto {$produto['codigo']}: " . $e->getMessage());
                    }
                }
                $currentPage++;
            } else {
                Log::error('Erro ao sincronizar produtos: ' . $response->body());
                break;
            }
        } while ($nextPageUrl);
    }
    
    public function handle()
    {
        $this->atualizarGrupos();
        $this->atualizarSubGrupos();
        $this->atualizarProdutos();
    }
}
