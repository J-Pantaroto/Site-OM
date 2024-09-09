<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CnpjCpf implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remove caracteres não numéricos
        $documento = preg_replace('/\D/', '', $value);
        // Verifica se é CPF ou CNPJ
        if (strlen($documento) === 11) {
            // Validar CPF
            if (!$this->validarCpf($documento)) {
                $fail('O CPF informado não é válido.');
            }
        } elseif (strlen($documento) === 14) {
            // Validar CNPJ
            if (!$this->validarCnpj($documento)) {
                $fail('O CNPJ informado não é válido.');
            }
        } else {
            // Documento inválido
            $fail('O CPF ou CNPJ informado não é válido.');
        }
    }

    /**
     * Valida o CPF.
     */
    private function validarCpf(string $cpf): bool
    {
        // Verifica se todos os dígitos são iguais (CPFs inválidos)
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Cálculo dos dígitos verificadores do CPF
        for ($t = 9; $t < 11; $t++) {
            $soma = 0;
            for ($i = 0; $i < $t; $i++) {
                $soma += $cpf[$i] * (($t + 1) - $i);
            }
            $resto = ($soma * 10) % 11;
            $resto = ($resto == 10) ? 0 : $resto;
            if ($cpf[$t] != $resto) {
                return false;
            }
        }

        return true;
    }

    /**
     * Valida o CNPJ.
     */
    private function validarCnpj(string $cnpj): bool
    {
        // Lógica de validação do CNPJ
        $soma = 0;
        $multiplicador = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        for ($i = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $multiplicador[$i];
        }

        $resto = $soma % 11;
        $primeiroDigito = ($resto < 2) ? 0 : 11 - $resto;

        if ($cnpj[12] != $primeiroDigito) {
            return false;
        }

        $soma = 0;
        $multiplicador = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

        for ($i = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $multiplicador[$i];
        }

        $resto = $soma % 11;
        $segundoDigito = ($resto < 2) ? 0 : 11 - $resto;

        return $cnpj[13] == $segundoDigito;
    }

    /**
     * Mensagem de erro para a validação.
     */
    public function message()
    {
        return 'O CPF ou CNPJ informado não é válido.';
    }
}


