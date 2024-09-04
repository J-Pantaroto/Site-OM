<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venda extends Model
{
    protected $table = 'vendas';
    use HasFactory;
    protected $fillable = ['data_venda', 'cliente_id'];
    public function itensVenda(){
        return $this->hasMany(ItensVenda::class, 'venda_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'cliente_id');
    }
}
