<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $table = 'states';

    // Campos que podem ser preenchidos
    protected $fillable = ['name', 'abbreviation'];


    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
