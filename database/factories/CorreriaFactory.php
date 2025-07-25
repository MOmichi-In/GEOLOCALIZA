<?php

namespace Database\Factories;

use App\Models\Correria;
use App\Models\Ciclo;
use Illuminate\Database\Eloquent\Factories\Factory;

class CorreriaFactory extends Factory
{
    protected $model = Correria::class;

    public function definition()
    {
        return [
            'nombre' => $this->faker->unique()->word(),
            'ciclo_id' => Ciclo::factory(),  // crea un ciclo asociado automáticamente
        ];
    }
}
