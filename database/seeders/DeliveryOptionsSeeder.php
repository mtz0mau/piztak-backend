<?php

namespace Database\Seeders;

use App\Models\DeliveryOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeliveryOptionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $deliveryOptions = [
            [ 'name' => 'sucursal' ],
            [ 'name' => 'domicilio', 'allow_delivery' => true, 'is_primary' => true ],
        ];

        foreach($deliveryOptions as $deliveryOption) DeliveryOption::create($deliveryOption);
    }
}
