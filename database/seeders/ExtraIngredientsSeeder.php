<?php

namespace Database\Seeders;

use App\Models\ExtraIngredient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExtraIngredientsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pizzasCategoryId = 1;

        $extraIngredients = [
            [
                'name' => 'orilla rellena',
                'category_id' => $pizzasCategoryId
            ],
            [
                'name' => 'queso',
                'category_id' => $pizzasCategoryId
            ],
            [
                'name' => 'pepperoni',
                'category_id' => $pizzasCategoryId
            ],
            [
                'name' => 'piña',
                'category_id' => $pizzasCategoryId
            ],
        ];

        foreach($extraIngredients as $extraIngredient) ExtraIngredient::create($extraIngredient);
    }
}
