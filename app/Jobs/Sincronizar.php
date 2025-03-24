<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Produto;
use App\Models\Grupo;
use App\Models\ImagemProduto;
use App\Models\SubGrupo;
use Illuminate\Support\Str;
use App\Http\Controllers\ProductNotificationController;

class Sincronizar implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {

    }

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

    public function salvarImagemBase64($base64String)
    {
        try {
            if (!preg_match('/^data:image\/(\w+);base64,/', $base64String)) {
                $base64String = 'data:image/jpeg;base64,' . $base64String;
            }

            if (preg_match('/^data:image\/(\w+);base64,/', $base64String, $matches)) {
                $imageType = strtolower($matches[1]);
                $base64String = preg_replace('/^data:image\/\w+;base64,/', '', $base64String);
            } else {
                $imageType = 'jpg';
            }

            $imageData = base64_decode($base64String);
            if ($imageData === false) {
                throw new \Exception('Base64 inválido ou corrompido');
            }

            $imageName = 'produtos/' . Str::random(40) . '.' . $imageType;

            Storage::disk('public')->put($imageName, $imageData);

            return $imageName;
        } catch (\Exception $e) {
            \Log::error("Erro ao salvar imagem base64: " . $e->getMessage());
            return 'produtos/placeholder.png';
        }
    }

    public function salvarImagemProduto($produto, $imagensBase64)
    {
        if (!empty($imagensBase64) && is_array($imagensBase64)) {
            foreach ($imagensBase64 as $index => $imagemBase64) {
                try {
                    $imageName = $this->salvarImagemBase64($imagemBase64);
                    $temPrincipal = ImagemProduto::where('produto_id', $produto->id)->where('principal', true)->exists();
                    $ehPrincipal = (!$temPrincipal && $index === 0);

                    $insercao = ImagemProduto::create([
                        'produto_id' => $produto->id,
                        'imagem' => $imageName,
                        'principal' => $ehPrincipal,
                    ]);

                    if ($ehPrincipal) {
                        $produto->update(['imagem' => $imageName]);
                    }
                } catch (\Exception $e) {
                    \Log::error("Erro ao salvar imagem do produto {$produto->codigo}: " . $e->getMessage());
                }
            }
        }
    }

    public function atualizarProdutos()
    {
        $currentPage = 1;
        do {
            $response = Http::get('http://192.168.1.50:22288/api/sales/produtos/imagens', [
                'quantidadeMaximaRegistros' => 500,
                'page' => $currentPage,
                'codigoMinimo' => 000100001,
            ]);

            if ($response->ok()) {
                $data = $response->json();
                $produtos = $data['data'] ?? [];
                $nextPageUrl = $data['next_page_url'];

                foreach ($produtos as $produto) {
                    if (empty($produto['descricao2'])) {
                        $nome = $produto['descricao'];
                        $descricao = $produto['descricao'];
                    } else {
                        $nome = $produto['descricao2'];
                        $descricao = $produto['descricao'];
                    }
                    try {
                        $produtoAtualizado = Produto::updateOrCreate(
                            ['codigo' => $produto['codigo']],
                            [
                                'nome' => $nome,
                                'descricao' => $descricao,
                                'slug' => Str::slug($produto['descricao'] ?? 'produto'),
                                'inativo' => $produto['inativo'],
                                'grupo' => $produto['grupo'],
                                'subgrupo' => $produto['subgrupo'],
                                'imagem' => 'produtos/placeholder.png',
                                'quantidade' => $produto['dados']['quantidade'] ?? 0,
                                'preco' => $produto['dados']['precoVenda'] ?? 0,
                            ]
                        );

                        if (!empty($produto['imagens'])) {
                            $this->salvarImagemProduto($produtoAtualizado, $produto['imagens']);
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
