<?php

namespace Database\Seeders;

use App\Models\CashRegister;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CashRegistersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CashRegister::create([
            'description' => '1',
            'balance' => 0
        ]);
    }
}
