<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItensVenda extends Model
{
    use HasFactory;
    protected $table = 'itensvendas';
    protected $fillable = ['venda_id','produto_id','quantidade','preco','subtotal'];

    public function venda(){
        return $this->belongsTo(Venda::class, 'venda_id');
    }
    public function produto(){
        return $this->belongsTo(Produto::class,'produto_id');
    }
}
