<?php

namespace Database\Seeders;

use App\Models\DeliveryOption;
use App\Models\District;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DistrictsDeliveryOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $districts = District::where('id', '!=', 1)->get();
        foreach($districts as $district){
            $district->deliveryOptions()->attach(2, ['price' => 15]);
        }
    }
}
