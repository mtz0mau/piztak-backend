<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = [
            [
                'name' => 'pizzas',
                'image' => 'images/01.webp'
            ],
            [
                'name' => 'carnes',
                'image' => 'images/02.webp'
            ],
            [
                'name' => 'tacos',
                'image' => 'images/05.webp'
            ],
            [
                'name' => 'postres',
                'image' => 'images/07.webp'
            ],
        ];

        foreach($categories as $categorie) Category::create($categorie);
    }
}