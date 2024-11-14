<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;
use Illuminate\Pagination\LengthAwarePaginator;

class ConfiguracoesController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $cores = collect(Config::get('config.colors'))->mapWithKeys(fn($value, $key) => [$key => $value]);
            $configuracoes = collect(Config::get('config.config'))->mapWithKeys(fn($value, $key) => [$key => $value]);
            $imagens = collect(is_array(Config::get('config.imgs')) ? Config::get('config.imgs') : []);


        if ($search) {
            $cores = $cores->filter(fn($value, $key) => Str::contains(Str::lower($key), Str::lower($search)));
            $configuracoes = $configuracoes->filter(fn($value, $key) => Str::contains(Str::lower($key), Str::lower($search)));
            $imagens = $imagens->filter(fn($value, $key) => Str::contains(Str::lower($key), Str::lower($search)));
        }
        $cores = $this->paginateCollection($cores, 12, $request);
        $configuracoes = $this->paginateCollection($configuracoes, 12, $request);
        $imagens = $this->paginateCollection($imagens, 12, $request);

        return view('configuracoes', [
            'cores' => $cores,
            'configuracoes' => $configuracoes,
            'imagens' => $imagens,
            'search' => $search,
        ]);
    }

    public function edit($configuracao)
    {
        $configuracoes = config('config.colors') + config('config.config') + config('config.imgs');

        if (!array_key_exists($configuracao, $configuracoes)) {
            return redirect()->route('configuracoes')->withErrors(['error' => 'Configuração não encontrada.']);
        }

        return view('configuracoes.editconfig', [
            'configuracao' => $configuracao,
            'value' => $configuracoes[$configuracao],
        ]);
    }

    public function update(Request $request, $configuracao)
    {
        $data = $request->validate([
            'value' => 'required|string',
        ]);

        $envKey = 'COLOR_' . strtoupper($configuracao);
        $this->updateEnvValue($envKey, $data['value']);

        return redirect()->route('configuracoes.index')->with('success', 'Configuração atualizada com sucesso!');
    }

    private function updateEnvValue($key, $value)
    {
        $envPath = base_path('.env');
        $content = file_get_contents($envPath);
        if (strpos($content, "{$key}=") !== false) {
            $content = preg_replace("/^{$key}=(.*)$/m", "{$key}=\"{$value}\"", $content);
        } else {
            $content .= "\n{$key}=\"{$value}\"";
        }

        file_put_contents($envPath, $content);
    }

    private function paginateCollection($collection, $perPage, $request)
    {
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $collection->slice(($currentPage - 1) * $perPage, $perPage);
    
        return new LengthAwarePaginator(
            $currentItems,
            $collection->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );
    }
    
}
