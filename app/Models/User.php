<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CustomResetPassword;
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
        'celular',
        'password',
        'state_id',
        'city_id',
        'address',
        'house_number',
        'neighborhood',
        'zip_code',
        'cpf_cnpj',
        'complement',
        'address_complete',
        'admin',
        'supervisor'
    ];
    protected $casts = [
        'address_complete' => 'boolean',
        'admin' => 'boolean',
        'supervisor' => 'boolean',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    public function hasVerifiedEmail(): bool
    {
        return !is_null($this->email_verified_at);
    }
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
    public function isAddressComplete(): bool
    {
        return !empty($this->address) &&
            !empty($this->house_number) &&
            !empty($this->neighborhood) &&
            !empty($this->city_id) &&
            !empty($this->state_id) &&
            !empty($this->zip_code);
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
    public function setCelularAttribute($value)
    {
        $this->attributes['celular'] = preg_replace('/\D/', '', $value);
    }

    /**
     * Accessor to format celular when accessing
     */
    public function getCelularAttribute($value)
    {
        return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1)$2-$3', $value);
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
    public function isSupervisor(): bool
    {
        return $this->supervisor;
    }
    public function vendas()
    {
        return $this->hasMany(Venda::class, 'cliente_id');
    }


    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\CustomVerifyEmail());
    }
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token));
    }
}
