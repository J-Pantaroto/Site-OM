<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitiesTableSeeder extends Seeder
{
    public function run()
    {
        $filePath = storage_path('app/cities.csv');
        $file = fopen($filePath, 'r');

        $stateMapping = DB::table('states')->pluck('id', 'abbreviation');

        $ufMapping = [
            11 => 'RO', 12 => 'AC', 13 => 'AM', 14 => 'RR', 15 => 'PA', 16 => 'AP', 17 => 'TO',
            21 => 'MA', 22 => 'PI', 23 => 'CE', 24 => 'RN', 25 => 'PB', 26 => 'PE', 27 => 'AL',
            28 => 'SE', 29 => 'BA', 31 => 'MG', 32 => 'ES', 33 => 'RJ', 35 => 'SP', 41 => 'PR',
            42 => 'SC', 43 => 'RS', 50 => 'MS', 51 => 'MT', 52 => 'GO', 53 => 'DF'
        ];

        fgetcsv($file);

        $cities = [];
        while (($data = fgetcsv($file)) !== false) {
            $ufCode = (int)$data[0];
            $ibgeCode = $data[1];
            $cityName = $data[2];
            $stateAbbreviation = $ufMapping[$ufCode] ?? null;

            if ($stateAbbreviation && isset($stateMapping[$stateAbbreviation])) {
                $cities[] = [
                    'name' => $cityName,
                    'ibge_code' => $ibgeCode,
                    'state_id' => $stateMapping[$stateAbbreviation],
                    'created_at' => now(), 
                    'updated_at' => now(),
                ];
            }
        }

        fclose($file);

        $chunks = array_chunk($cities, 500);
        foreach ($chunks as $chunk) {
            DB::table('cities')->insert($chunk);
        }
    }
}