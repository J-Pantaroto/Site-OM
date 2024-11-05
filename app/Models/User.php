<?php

namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'cpf_cnpj'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    public function setCpfCnpjAttribute($value)
    {
        // Remove todos os caracteres que não são números
        $this->attributes['cpf_cnpj'] = preg_replace('/\D/', '', $value);
    }

    public function getCpfCnpjFormattedAttribute()
    {
        $cpf_cnpj = $this->attributes['cpf_cnpj'];

        if (strlen($cpf_cnpj) === 11) {
            return preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "$1.$2.$3-$4", $cpf_cnpj);
        } elseif (strlen($cpf_cnpj) === 14) {
            return preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "$1.$2.$3/$4-$5", $cpf_cnpj);
        }

        return $cpf_cnpj;
    }
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function isAdmin()
{
    return $this->admin;
}
public function vendas()
{
    return $this->hasMany(Venda::class, 'cliente_id');
}

}
