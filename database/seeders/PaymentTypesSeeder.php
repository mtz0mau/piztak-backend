<?php

namespace Database\Seeders;

use App\Models\PaymentType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $paymentTypes = [
            [
                'name' => 'efectivo',
                'card_accepted' => false,
                'cash_accepted' => true,
            ],
            [
                'name' => 'tarjeta',
                'card_accepted' => true,
                'cash_accepted' => false,
            ]
        ];

        foreach($paymentTypes as $paymentType) PaymentType::create($paymentType);
    }
}
