<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UsersSeeder::class);
        $this->call(CategoriesSeeder::class);
        $this->call(ExtraIngredientsSeeder::class);
        $this->call(SizesSeeder::class);
        $this->call(SizeExtraIngredientSeeder::class);
        $this->call(PizzasSeeder::class);
        $this->call(CarnesSeeder::class);
        $this->call(RolesSeeder::class);
        $this->call(UserRoleSeeder::class);
        $this->call(DeliveryOptionsSeeder::class);
        $this->call(DistrictsSeeder::class);
        $this->call(DistrictsDeliveryOptionsSeeder::class);
        $this->call(PaymentTypesSeeder::class);
        $this->call(CashRegistersSeeder::class);
        $this->call(CustomersSeeder::class);
    }
}