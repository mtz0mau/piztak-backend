<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Size;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CarnesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $carnesCategoryId = 2;
        $path = 'images/';

        $products = [
            [
                'name' => 'matahambre',
                'image' => $path.'matahambre.webp',
                'category_id' => $carnesCategoryId,
            ],
            [
                'name' => 'al pastor',
                'image' => $path.'pastor.webp',
                'category_id' => $carnesCategoryId,
            ],
        ];

        $sizes = Size::where('category_id', $carnesCategoryId)->get();

        $pricesSizes = [
            [
                5 => 180, // Grande
                6 => 90 // Chico
            ],
            [
                5 => 240, // Grande
                6 => 130 // Chico
            ],
            [
                5 => 240, // Grande
                6 => 130 // Chico
            ]
        ];

        foreach($products as $i => $product){
            $product = Product::create($product);
            foreach($sizes as $size){
                $product->sizes()->attach($size->id, [ 'price' => $pricesSizes[$i][$size->id]]);
            }
        }
    }
}
