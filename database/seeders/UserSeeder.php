<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            
            'name' => 'Santiago Gonzales',
            'email' => 'santiago.gonzales@rib.com.co',
            'password' => Hash::make('1052086011'),
            'rol' => 'SUPER',
        ]);

    }
}
