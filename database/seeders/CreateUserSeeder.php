<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class CreateUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users  = [
            [
                'name' => 'isUser',
                'username' => "isUser",
                'email' => 'user@gmail.com',
                'password' => bcrypt('12345'),
                'roles_id' => 2
            ],
            [
                'name' => 'isAdmin',
                'username' => "isAdmin",
                'email' => 'admin@gmail.com',
                'password' => bcrypt('12345'),
                'roles_id' => 1
            ]
        ];

        foreach ($users as $key => $user) {
            User::create($user);
        }
    }
}
