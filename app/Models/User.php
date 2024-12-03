<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;



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
        'state_id',
        'city_id',
        'address',
        'house_number',
        'neighborhood',
        'zip_code',
        'complement',
        'address_complete',
        'admin'
    ];
    protected $casts = [
        'address_complete' => 'boolean',
        'admin' => 'boolean',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    public function state()
    {
        return $this->belongsTo(State::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }
    public function zipCode(): Attribute
    {
        return Attribute::make(
            get: fn($value) => preg_replace('/(\d{5})(\d{3})/', '$1-$2', $value),

            set: fn($value) => str_replace('-', '', $value)
        );
    }
    public function cpfCnpj(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (!$value) {
                    return null;
                }

                if (strlen($value) === 11) {
                    return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $value);
                } elseif (strlen($value) === 14) {
                    return preg_replace('/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/', '$1.$2.$3/$4-$5', $value);
                }

                return $value;
            },
            set: fn($value) => preg_replace('/\D/', '', $value)
        );
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
    public function isAdmin(): bool
    {
        return $this->admin;
    }
    public function vendas()
    {
        return $this->hasMany(Venda::class, 'cliente_id');
    }
}
