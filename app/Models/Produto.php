<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
  protected $table = 'produtos';
  use HasFactory;
  protected $fillable = ['nome', 'descricao', 'imagem'];
  public function imagens()
  {
    return $this->hasMany(ImagemProduto::class);
  }
  public function grupos()
  {
    return $this->belongsTo(Grupo::class, 'grupo_id');
  }
  public function preco(): Attribute
  {
    return Attribute::make(
      get: function ($value) {
        if ($value !== null && is_numeric($value)) {
          return number_format((float) $value, 2, ',', '.');
        }
        return $value;
      },
      set: function ($value) {
        if ($value !== null) {
          $value = str_replace(',', '.', $value);
          return floatval($value);
        }
        return $value;
      }
    );
  }
}
