<?php

namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DefaultSupervisorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verifica se já existe um supervisor padrão
        if (!User::where('email', 'suporte@omturbo.com')->exists()) {
            User::create([
                'name' => 'Supervisor',
                'email' => 'suporte@omturbo.com',
                'celular' => '6721082600',
                'state_id' => '12',
                'city_id' => '5135',
                'address' => 'Av. Weimar Gonçalves Torres',
                'house_number' => '3071',
                'complement' => 'OM INFORMATICA',
                'neighborhood' => 'Centro',
                'zip_code'=>'79801-004',
                'cpf_cnpj' => '00189631000109',
                'email_verified_at' => NOW(),
                'created_at' => NOW(),
                'password' => Hash::make('231165'), 
                'admin' => false,
                'supervisor' => true,
                'address_complete' => true,
            ]);
        }
    }
}
