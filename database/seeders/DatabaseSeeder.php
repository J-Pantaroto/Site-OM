<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\StatesTableSeeder;
use Database\Seeders\CitiesTableSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(StatesTableSeeder::class);
        $this->call(CitiesTableSeeder::class);
       $this->call(DefaultSupervisorSeeder::class);
    }
}
