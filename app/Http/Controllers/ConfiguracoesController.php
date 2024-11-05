<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class ConfiguracoesController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // Carrega todas as configurações, incluindo as cores
        $configuracoes = collect(Config::get('config'))
            ->map(function ($value, $key) {
                return ['key' => $key, 'value' => $value];
            });

        // Filtra as configurações se houver um termo de pesquisa
        if ($search) {
            $configuracoes = $configuracoes->filter(function ($configuracao) use ($search) {
                return str_contains(strtolower($configuracao['key']), strtolower($search));
            });
        }

        return view('configuracoes', [
            'configuracoes' => $configuracoes,
            'search' => $search,
        ]);
    }

    public function edit($configuracao)
    {
        // Obtém a configuração específica para edição
        $configuracoes = config('config');
        if (!array_key_exists($configuracao, $configuracoes)) {
            return redirect()->route('configuracoes.index')->withErrors(['error' => 'Configuração não encontrada.']);
        }

        return view('configuracoes.editconfig', [
            'configuracao' => $configuracao,
            'value' => $configuracoes[$configuracao],
        ]);
    }

    public function update(Request $request, $configuracao)
    {
        $data = $request->validate([
            'value' => 'required|string', // Valida a entrada como string
        ]);

        $envPath = base_path('.env');
        $envKey = 'COLOR_' . strtoupper($configuracao);

        // Atualiza o valor da configuração no .env
        $content = file_get_contents($envPath);
        $pattern = "/^{$envKey}=(.*)$/m";
        $replacement = "{$envKey}=\"{$data['value']}\"";
        $content = preg_replace($pattern, $replacement, $content);
        file_put_contents($envPath, $content);

        // Atualiza o cache de configuração
        //Config::call('config:cache');

        return redirect()->route('configuracoes')->with('success', 'Configuração atualizada com sucesso!');
    }
}
