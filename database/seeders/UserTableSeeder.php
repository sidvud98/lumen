<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Tarun', 
            'email' => 'Tarun@gmail.com',
            'password' => Hash::make('12345678'),
            'role' => 'Admin',
            'creator' => 'someone'

        ]);
    }
}