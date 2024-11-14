<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImagemProduto extends Model
{
    use HasFactory;
protected $table = 'imagens_produtos';
    protected $fillable = ['produto_id', 'imagem','principal'];

    public function produto()
    {
        return $this->belongsTo(Produto::class);
    }
}
