<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class ConfiguracoesController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');

        $configuracoes = collect(Config::get('config.colors'))
            ->mapWithKeys(fn($value, $key) => ['COLOR_' . $key => $value])
            ->merge(
                collect(Config::get('config.config'))
                    ->mapWithKeys(fn($value, $key) => [$key => $value])
            )
            ->merge(
                collect(Config::get('config.imgs'))
                    ->mapWithKeys(fn($value, $key) => ['IMG_' . $key => $value])
            );

        if ($search) {
            $configuracoes = $configuracoes->filter(fn($value, $key) => Str::contains(Str::lower($key), Str::lower($search)));
        }

        if ($category) {
            $configuracoes = $configuracoes->filter(function ($value, $key) use ($category) {
                if ($category === 'cores' && Str::startsWith($key, 'COLOR_')) {
                    return true;
                }
                if ($category === 'imagens' && Str::startsWith($key, 'IMG_')) {
                    return true;
                }
                if ($category === 'gerais' && !Str::startsWith($key, 'COLOR_') && !Str::startsWith($key, 'IMG_')) {
                    return true;
                }
                return false;
            });
        }

        $configuracoes = $this->paginateCollection($configuracoes, 12, $request);

        return view('configuracoes', compact('configuracoes', 'search', 'category'));
    }


    public function edit($configuracao)
    {
        $configuracoes = collect(Config::get('config.colors'))
            ->mapWithKeys(fn($value, $key) => ['COLOR_' . $key => $value])
            ->merge(
                collect(Config::get('config.config'))
                    ->mapWithKeys(fn($value, $key) => [$key => $value])
            )
            ->merge(
                collect(Config::get('config.imgs'))
                    ->mapWithKeys(fn($value, $key) => ['IMG_' . $key => $value])
            );

        if (!isset($configuracoes[$configuracao])) {
            return redirect()->route('configuracoes')->withErrors(['error' => 'Configuração não encontrada.']);
        }
        $tipo = 'geral';
        if (Str::startsWith($configuracao, 'COLOR_')) {
            $tipo = 'cor';
        } elseif (Str::startsWith($configuracao, 'IMG_')) {
            $tipo = 'imagem';
        }

        return view('configuracoes.editconfig', [
            'configuracao' => $configuracao,
            'value' => $configuracoes[$configuracao],
            'tipo' => $tipo,
        ]);
    }
    public function update(Request $request, $configuracao)
    {
        $envKey = strtoupper($configuracao);
        $isImage = Str::startsWith($configuracao, 'IMG_');
        $data = $request->validate([
            'value' => $isImage ? 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' : 'required|string|max:255',
        ]);

        try {
            if ($isImage && $request->hasFile('value')) {
                $file = $request->file('value');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->move(public_path('images'), $filename);

                $relativePath = 'images/' . $filename;
                $this->updateEnvValue($envKey, $relativePath);

                config(['config.imgs.' . Str::snake(substr($configuracao, 4)) => $relativePath]);
            } else {
                $value = $data['value'];
                $this->updateEnvValue($envKey, $value);

                if (Str::startsWith($configuracao, 'COLOR_')) {
                    config(['config.colors.' . Str::snake(substr($configuracao, 6)) => $value]);
                } else {
                    config(['config.config.' . Str::snake($configuracao) => $value]);
                }
            }
        } catch (\Exception $e) {
            Log::error("Erro ao atualizar configuração: " . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Falha ao atualizar a configuração.']);
        }

        return redirect()->route('configuracoes')->with('success', 'Configuração atualizada com sucesso!');
    }
    private function updateEnvValue($key, $value)
    {
        Log::info("Atualizando .env: {$key} = {$value}");

        $envPath = base_path('.env');

        if (!file_exists($envPath)) {
            Log::error("Arquivo .env não encontrado.");
            throw new \Exception("Arquivo .env não encontrado.");
        }

        $content = file_get_contents($envPath);
        if ($content === false) {
            Log::error("Falha ao ler o arquivo .env.");
            throw new \Exception("Falha ao ler o arquivo .env.");
        }

        if (strpos($content, "{$key}=") !== false) {
            $content = preg_replace("/^{$key}=.*$/m", "{$key}=\"{$value}\"", $content);
        } else {
            $content .= "\n{$key}=\"{$value}\"";
        }

        if (file_put_contents($envPath, $content) === false) {
            Log::error("Falha ao salvar o arquivo .env.");
            throw new \Exception("Falha ao salvar o arquivo .env.");
        }

        Log::info("Atualização concluída: {$key} = {$value}");
    }


    private function paginateCollection($collection, $perPage, $request)
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->all();

        return new LengthAwarePaginator(
            $currentItems,
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }
}
