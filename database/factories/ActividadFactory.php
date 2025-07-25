<?php

namespace Database\Factories;

use App\Models\Actividad;
use Illuminate\Database\Eloquent\Factories\Factory;

class ActividadFactory extends Factory
{
    protected $model = Actividad::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->unique()->word(),
        ];
    }
}
