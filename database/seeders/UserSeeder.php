<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\TareaAsignada;
use App\Models\Ciclo;
use App\Models\Correria;
use App\Models\Actividad;

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
