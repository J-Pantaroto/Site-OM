<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $table = 'grupos';
    protected $fillable = ['descricao','codigo'];
    use HasFactory;
    public function produtos()
    {
        return $this->hasMany(Produto::class, 'grupo');
    }
}
