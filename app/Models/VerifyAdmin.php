<?php

namespace App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class VerifyAdmin extends Model
{
    use HasFactory;

    protected $table = 'verificacoes'; 

    protected $fillable = [
        'user_id',
        'hash',
        'user_verified_at',
    ];

    public static function createHash(User $user){
        $registro = self::create([
            'hash' => Str::uuid(),
            'user_id' => $user->id
        ]);
        $admin = User::where('email', 'jhonatanpantaroto@gmail.com')->first();
        $admin->notify(new \App\Notifications\UserVerificationRequestNotification($registro));
    }

    //CASO SEJA NECESSARIO RETORNAR QUANDO FOI VERIFICADO PELO ADM
    protected function casts(): array
{
    return [
        'user_verified_at' => 'datetime',
    ];
}


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
