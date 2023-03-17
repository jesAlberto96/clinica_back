<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Manager',
            'email' => 'manager@manager.com',
            'password' => bcrypt('secret'), //Contraseña: secret
            'remember_token' => Str::random(10),
        ]);
    }
}
