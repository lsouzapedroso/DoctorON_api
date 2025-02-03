<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MedicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            DB::table('medics')->insert([
                'name' => "Medic $i",
                'specialization' => "Specialization $i",
                'city_id' => rand(1, 26),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
