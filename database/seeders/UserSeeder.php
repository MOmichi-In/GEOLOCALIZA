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
            'name' => 'Proyecto',
            'email' => 'proyecto@ejemplo.com',
            'password' => Hash::make('password123'),
            'rol' => 'Lider_Proyecto_Analista',
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@ejemplo.com',
            'password' => Hash::make('password123'),
            'rol' => 'Coordinador_Administrativo',
        ]);
        
    }
}
