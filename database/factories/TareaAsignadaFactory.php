<?php

namespace Database\Factories;

use App\Models\TareaAsignada;
use App\Models\User;
use App\Models\Ciclo;
use App\Models\Correria;
use App\Models\Actividad;
use Illuminate\Database\Eloquent\Factories\Factory;

class TareaAsignadaFactory extends Factory
{
    protected $model = TareaAsignada::class;

    public function definition()
    {
        // Obtener IDs de operadores y supervisores
        $operadores = User::where('rol', User::ROLE_OPERADOR_LOGISTICO)->pluck('id')->toArray();
        $supervisores = User::where('rol', User::ROLE_SUPERVISOR)->pluck('id')->toArray();
        $ciclos = Ciclo::pluck('id')->toArray();
        $correrias = Correria::pluck('id')->toArray();
        $actividades = Actividad::pluck('id')->toArray();

        // Fechas: inicio antes que entrega
        $fechaInicio = $this->faker->dateTimeBetween('-10 days', 'now');
        $fechaEntrega = $this->faker->dateTimeBetween($fechaInicio, '+10 days');

        return [
            'operador_id' => $this->faker->randomElement($operadores) ?? User::factory()->create(['rol' => User::ROLE_OPERADOR_LOGISTICO])->id,
            'supervisor_id' => $this->faker->randomElement($supervisores) ?? User::factory()->create(['rol' => User::ROLE_SUPERVISOR])->id,
            'ciclo_id' => $this->faker->randomElement($ciclos) ?? Ciclo::factory()->create()->id,
            'correria_id' => $this->faker->randomElement($correrias) ?? Correria::factory()->create()->id,
            'actividad_id' => $this->faker->randomElement($actividades) ?? Actividad::factory()->create()->id,
            'cantidad' => $this->faker->numberBetween(1, 50),
            'fecha_inicio' => $fechaInicio,
            'fecha_entrega' => $fechaEntrega,
            'observaciones' => $this->faker->optional()->sentence(),
            'firma_inicio' => $this->faker->optional()->sha1(),
            'firma_final' => $this->faker->optional()->sha1(),
            'firma_supervisor' => $this->faker->optional()->sha1(),
            'estado' => $this->faker->randomElement(['Asignada', 'Finalizada', 'Pendiente']),
        ];
    }
}
