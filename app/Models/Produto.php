<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
  protected $table = 'produtos';
  use HasFactory;
  protected $fillable = ['nome', 'descricao'];
  public function imagens()
  {
    return $this->hasMany(ImagemProduto::class);
  }
  public function grupos()
  {
    return $this->belongsTo(Grupo::class, 'grupo_id');
  }
}
