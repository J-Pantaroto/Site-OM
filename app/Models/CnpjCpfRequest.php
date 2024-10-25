<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CnpjCpfRequest extends Model
{
    protected $fillable = ['cpf_cnpj', 'response'];
    protected $casts = [
        'response' => 'array',
    ];
}
