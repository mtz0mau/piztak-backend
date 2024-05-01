<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SizesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pizzasCategoryId = 1;
        $carnesCategoryId = 2;
        $tacosCategoryId = 3;
        $postresCategoryId = 4;

        $sizes = [
            [
                'name' => 'jumbo',
                'description' => '36 cuadros',
                'category_id' => $pizzasCategoryId
            ],
            [
                'name' => 'familiar',
                'description' => '16 rebanadas',
                'category_id' => $pizzasCategoryId
            ],
            [
                'name' => 'mediana',
                'description' => '8 rebanadas',
                'category_id' => $pizzasCategoryId
            ],
            [
                'name' => 'chica',
                'description' => '6 rebanadas',
                'category_id' => $pizzasCategoryId
            ],
            [
                'name' => 'grande',
                'description' => '1 kilo',
                'category_id' => $carnesCategoryId
            ],
            [
                'name' => 'chico',
                'description' => '500 g',
                'category_id' => $carnesCategoryId
            ],
            [
                'name' => 'orden',
                'description' => '5 pzas',
                'category_id' => $tacosCategoryId
            ],
            [
                'name' => 'rebanada',
                'description' => '',
                'category_id' => $postresCategoryId
            ]
        ];

        foreach($sizes as $size) Size::create($size);
    }
}
