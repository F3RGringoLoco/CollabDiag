<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'admin',
            'password' => bcrypt(1234),
            'remember_token' =>  Str::random(60),
            'email' => 'fercho@gmail.com',
        ]);
        User::create([
            'name' => 'guest1',
            'password' => bcrypt(1234),
            'remember_token' =>  Str::random(60),
            'email' => 'guest1@gmail.com',
        ]);
        User::create([
            'name' => 'guest2',
            'password' => bcrypt(1234),
            'remember_token' =>  Str::random(60),
            'email' => 'guest2@gmail.com',
        ]);
    }
}
