<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Size;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PizzasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pizzasCategoryId = 1;
        $path = 'images/';

        $products = [
            [
                'name' => 'pepperoni',
                'image' => $path.'p-peperoni.webp',
                'category_id' => $pizzasCategoryId,
                'is_special' => false
            ],
            [
                'name' => 'hawaiana',
                'image' => $path.'p-haw.webp',
                'category_id' => $pizzasCategoryId,
                'is_special' => false
            ],
            [
                'name' => 'pastor',
                'image' => $path.'p-pastor.webp',
                'category_id' => $pizzasCategoryId,
                'is_special' => true
            ],
            [
                'name' => 'suprema',
                'image' => $path.'p-supre.webp',
                'category_id' => $pizzasCategoryId,
                'is_special' => true
            ],
            [
                'name' => 'choriqueso',
                'image' => $path.'p-choriqueso.webp',
                'category_id' => $pizzasCategoryId,
                'is_special' => true
            ],
            [
                'name' => 'napolitana',
                'image' => $path.'p-napo.webp',
                'category_id' => $pizzasCategoryId,
                'is_special' => true
            ],
            [
                'name' => 'mexicana',
                'image' => $path.'p-mexicana.webp',
                'category_id' => $pizzasCategoryId,
                'is_special' => true
            ],
            [
                'name' => 'carnes frías',
                'image' => $path.'p-carnes.webp',
                'category_id' => $pizzasCategoryId,
                'is_special' => true
            ],
            [
                'name' => 'vegetariana',
                'image' => $path.'p-vegetariana.webp',
                'category_id' => $pizzasCategoryId,
                'is_special' => true
            ]
        ];

        $sizes = Size::where('category_id', $pizzasCategoryId)->get();

        $normalPrices = [
            1 => 180, // jumbo
            2 => 130, // familiar
            3 => 95, // mediana
            4 => 60 // chica
        ];

        $specialPrices = [
            1 => 225, // jumbo
            2 => 170, // familiar
            3 => 130, // mediana
            4 => 85 // chica
        ];

        foreach($products as $product){
            $product = Product::create($product);
            foreach($sizes as $size){
                $product->sizes()->attach($size->id, [ 'price' => $product->is_special ? $specialPrices[$size->id] : $normalPrices[$size->id] ]);
            }
        }
    }
}
