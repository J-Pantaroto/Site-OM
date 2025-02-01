<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Produto extends Model
{
  protected $table = 'produtos';
  use HasFactory;
  protected $fillable = ['nome', 'descricao', 'imagem', 'codigo', 'inativo', 'grupo', 'subgrupo', 'slug', 'quantidade','preco'];

//gerar slug para url
  protected static function boot()
  {
    parent::boot();

    static::saving(function ($produto) {
      $produto->slug = Str::slug($produto->nome);
    });
  }

  public function imagens()
  {
    return $this->hasMany(ImagemProduto::class);
  }
  public function grupos()
  {
    return $this->belongsTo(Grupo::class, 'grupo');
  }
  public function subgrupo()
  {
    return $this->belongsTo(Subgrupo::class, 'subgrupo', 'codigo');
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
