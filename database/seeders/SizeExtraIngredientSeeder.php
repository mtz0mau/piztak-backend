<?php

namespace Database\Seeders;

use App\Models\Size;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SizeExtraIngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sizes = [
            1,
            2,
            3,
            4
        ];

        $extraIngredients = [
            4 => [
                1 => 10,
                2 => 10,
                3 => 10,
                4 => 10
            ],
            3 => [
                1 => 30,
                2 => 15,
                3 => 15,
                4 => 15
            ],
            2 => [
                1 => 40,
                2 => 20,
                3 => 20,
                4 => 20
            ],
            1 => [
                1 => 60,
                2 => 30,
                3 => 30,
                4 => 30
            ],
        ];

        foreach($sizes as $sizeId){
            $size = Size::find($sizeId);
            foreach($extraIngredients[$size->id] as $extraIngredientId => $extraIngredientPrice){
                $size->extraIngredients()->attach($extraIngredientId, [ 'price' => $extraIngredientPrice ]);
            }
        }
    }
}