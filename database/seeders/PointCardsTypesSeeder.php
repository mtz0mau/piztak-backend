<?php

namespace Database\Seeders;

use App\Models\PointCardType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PointCardsTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PointCardType::create([
            'name' => 'Normal',
            'discount' => 1
        ]);
    }
}
