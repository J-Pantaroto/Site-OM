<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\CnpjCpfRequest;

class CnpjCpfService
{
    public function verificarCnpj($cnpj)
    {
        $consultaExistente = CnpjCpfRequest::where('cpf_cnpj', $cnpj)->first();

        if ($consultaExistente) {
            return $consultaExistente->response; // Retorna a resposta do cache
        }

        $url = "https://brasilapi.com.br/api/cnpj/v1/{$cnpj}";
        $response = Http::get($url);

        if ($response->successful()) {
            $dados = $response->json();
            CnpjCpfRequest::create([
                'cpf_cnpj' => $cnpj,
                'response' => $dados,
            ]);
            if ($dados['situacao_cadastral'] != 2) {
                return [
                    'message' => 'CNPJ inválido ou inativo.',
                    'data' => $dados,
                ];
            }
            return [
                'message' => 'CNPJ válido.',
                'data' => $dados,
            ];
        }
        \Log::error('Erro ao consultar CNPJ', [
            'cnpj' => $cnpj,
            'status' => $response->status(),
            'body' => $response->body(),
        ]);
        return null;
    }

}
