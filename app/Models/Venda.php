<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;


class Venda extends Model
{
    protected $table = 'vendas';
    use HasFactory;
    protected $fillable = ['data_venda', 'cliente_id','total'];
    public function itensVenda(){
        return $this->hasMany(ItensVenda::class, 'venda_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'cliente_id');
    }

    public function dataVenda(): Attribute
{
    return Attribute::make(
        get: function ($value) {
            if (!$value) {
                return null;
            }
            return \Carbon\Carbon::createFromFormat('Y-m-d', $value)->format('d/m/Y');
        },
        set: function ($value) {
            if (!$value) {
                return null;
            }
            return \Carbon\Carbon::createFromFormat('d/m/Y', $value)->format('Y-m-d');
        }
    );
}
}
