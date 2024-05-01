<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'username' => 'mauricio',
                'email' => 'mtz0mau2002@gmail.com',
                'password' => bcrypt('123456'),
            ],
            [
                'username' => 'job01',
                'email' => 'job01piztak@gmail.com',
                'password' => bcrypt('123456'),
            ],
            [
                'username' => 'angelina',
                'email' => 'ange0mtz1981@gmail.com',
                'password' => bcrypt('123456'),
            ],
            [
                'username' => 'dulce',
                'email' => 'dulce@gmail.com',
                'password' => bcrypt('123456'),
            ],
            [
                'username' => 'luz',
                'email' => 'luz@gmail.com',
                'password' => bcrypt('123456'),
            ],
            [
                'username' => 'edmundo',
                'email' => 'edmundo_rb@hotmail.com',
                'password' => bcrypt('123456'),
            ],
        ];
        
        foreach($users as $user) User::create($user);
    }
}
