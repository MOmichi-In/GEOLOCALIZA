<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;
    protected static $codigoSupervisorCounter = 1;  // <-- acá la defines

    public function definition()
    {
        // Asumamos que los roles son los que definiste en el modelo
        $roles = User::$availableRoles;

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // o Hash::make('password')
            'rol' => $this->faker->randomElement($roles),
            'cedula' => $this->faker->unique()->regexify('[0-9]{7,8}'),
            'codigo_supervisor' => str_pad(self::$codigoSupervisorCounter++, 4, '0', STR_PAD_LEFT),
            'remember_token' => Str::random(10),
            'unidad_trabajo_id' => null, // Aquí puedes poner un valor por defecto o asignar en el Seeder
        ];
    }

    // Opcional: scopes para roles
    public function supervisor()
    {
        return $this->state(fn() => ['rol' => User::ROLE_SUPERVISOR]);
    }

    public function operadorLogistico()
    {
        return $this->state(fn() => ['rol' => User::ROLE_OPERADOR_LOGISTICO]);
    }

    // etc para otros roles si quieres
}
