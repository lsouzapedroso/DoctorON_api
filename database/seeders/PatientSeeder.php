<?php

namespace Database\Seeders;

use App\Models\Patient;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('pt_BR');
        foreach (range(1, 10) as $index) {
            Patient::create([
                'name' => $faker->name,
                'cpf' => $faker->unique()->cpf,
                'phone' => $faker->cellphoneNumber,
            ]);
        }
    }
}
