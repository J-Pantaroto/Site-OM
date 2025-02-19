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
  protected $fillable = ['nome', 'descricao', 'imagem', 'codigo', 'inativo', 'grupo', 'subgrupo', 'slug', 'quantidade','preco','inativo_site'];

//gerar slug para url
  protected static function boot()
  {
    parent::boot();

    static::saving(function ($produto) {
      $produto->slug = Str::slug($produto->nome);
    });
  }

  public function verificarDadosParaAtualizar($campo){
    $dadosPermitidos = config('config.config.dados_produtos_para_sincronizar');

      $camposParaSincronizar = explode(',', str_replace(' ', '', $dadosPermitidos));

      $camposObrigatorios = ['inativo', 'quantidade', 'grupo', 'subgrupo'];
      if(in_array('todos',$camposParaSincronizar )){
        return true;
      }
      if (in_array($campo, $camposParaSincronizar) || in_array($campo, $camposObrigatorios)) {
          return true;
      }

      return false;
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
          get: fn ($value) => $value !== null ? (float) $value : null, // Retorna float puro, sem formatação
          set: fn ($value) => $value !== null ? (float) str_replace(',', '.', $value) : null
      );
  }
  
}
