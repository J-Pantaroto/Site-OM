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
use App\Models\Venda;
use App\Models\User;

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
        $currentPage = 1;
        do {
            $response = Http::get('http://192.168.1.50:22288/api/sales/produtos?', [
                'quantidadeMaximaRegistros' => 500,
                'page' => $currentPage,
            ]);

            if ($response->ok()) {
                $data = $response->json();
                $produtos = $data['data'] ?? [];
                $nextPageUrl = $data['next_page_url'];

                foreach ($produtos as $produto) {
                    if ($produto['inativo'] === 'S') {
                        continue;
                    }
                    $nomeProduto = $produto['descricao'] ?? 'Produto Sem Nome';
                    $dadosProduto = $produto['dados'] ?? [];
                    $quantidade = $dadosProduto['quantidade'] ?? 0;
                    $precoVenda = $dadosProduto['precoVenda'] ?? 0;
                    $imagem = $produto['A_IMAG'] ?? 'produtos/placeholder.png';
                    $nomeProduto = $produto['descricao'];
                    $slugBase = Str::slug($nomeProduto);
                    $slug = $slugBase;
                    $contador = 1;

                    // Garante que o slug seja único
                    while (Produto::where('slug', $slug)->exists()) {
                        $slug = "{$slugBase}-{$contador}";
                        $contador++;
                    }
                    Produto::updateOrCreate(
                        ['codigo' => $produto['codigo']],
                        [
                            'codigo' => $produto['codigo'],
                            'nome' => $nomeProduto,
                            'descricao' => $nomeProduto,
                            'slug' => $slug,
                            'inativo' => $produto['inativo'],
                            'grupo' => $produto['grupo'],
                            'subgrupo' => $produto['subgrupo'],
                            'imagem' => $imagem,
                            'quantidade' => $quantidade, 
                            'preco' => $precoVenda
                        ]
                    );
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

