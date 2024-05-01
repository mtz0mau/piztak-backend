<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $managerRole = Role::find(1);
        $dilveryDriverRole = Role::find(3);
        $cashierRole = Role::find(4);
        $posRole = Role::find(5);

        $managerRole->users()->attach(1);
        $dilveryDriverRole->users()->attach(1);
        $cashierRole->users()->attach(1);

        $posRole->users()->attach(2);

        $cashierRole->users()->attach(3);
        $cashierRole->users()->attach(4);

        $dilveryDriverRole->users()->attach(5);
        $cashierRole->users()->attach(5);

        $managerRole->users()->attach(6);
        $dilveryDriverRole->users()->attach(6);
        $cashierRole->users()->attach(6);
    }
}
