<?php

namespace Database\Factories;

use App\Models\Ciclo;
use Illuminate\Database\Eloquent\Factories\Factory;

class CicloFactory extends Factory
{
    protected $model = Ciclo::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->unique()->word(), // nombre único
        ];
    }
}
