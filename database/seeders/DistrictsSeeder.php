<?php

namespace Database\Seeders;

use App\Models\District;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DistrictsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $districts = [
            [
                'name' => 'Colinas de San José',
            ],
            [
                'name' => 'Colonia Esperanza',
            ],
            [
                'name' => 'Tapia',
            ],
            [
                'name' => 'Aquiles',
            ],
            [
                'name' => 'Trapiche',
            ],
            [
                'name' => '20 de Noviembre',
            ],
            [
                'name' => 'San Martín',
            ],
            [
                'name' => 'Villa Libertad',
            ],
            [
                'name' => 'Naranjal',
            ],
        ];

        foreach($districts as $district){
            District::create($district);
        }
    }
}
