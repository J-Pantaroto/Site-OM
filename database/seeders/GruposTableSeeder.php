<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class GruposTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('grupos')->insert([
            ['descricao' => 'Acessórios'],
            ['descricao' => 'Peças Automotivas'],
            ['descricao' => 'Peças Esportivas'],
            ['descricao' => 'Iluminação'],
            ['descricao' => 'Rodas'],
            ['descricao' => 'Manutenção'],
            ['descricao' => 'Resfriamento'],
            ['descricao' => 'Elétrica'],
            ['descricao' => 'Transmissão'],
            ['descricao' => 'Suspensão'],
            ['descricao' => 'Freios'],
            ['descricao' => 'Segurança'],

        ]);
    }
}
