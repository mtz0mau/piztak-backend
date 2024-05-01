<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [ 'name' => 'manager' ],
            [ 'name' => 'customer' ],
            [ 'name' => 'delivery_driver' ],
            [ 'name' => 'cashier' ],
            [ 'name' => 'pos' ],
        ];
        
        foreach($roles as $role){
            Role::create($role);
        }
    }
}