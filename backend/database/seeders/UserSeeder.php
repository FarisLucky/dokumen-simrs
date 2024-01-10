<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

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
            'name' => 'salman',
            'username' => 'salman',
            'email' => 'salman@gmail.com',
            'password' => bcrypt(123),
            'role' => 'ADMIN',
            'api_key' => md5('salman'),
        ]);
    }
}
