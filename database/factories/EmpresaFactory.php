<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Empresa>
 */
class EmpresaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $prefijo = fake()->randomElement(['20', '23', '24', '27', '30', '33', '34']); // Prefijos válidos
        $dni = fake()->numerify('########'); // 8 dígitos
        $digitoVerificador = fake()->numerify('#'); // 1 dígito al final
        
        
        return [
            'nombre'=> $this->faker->company(),
            'cuil_cuit'=> "{$prefijo}-{$dni}-{$digitoVerificador}",
            'referente'=> $this->faker->lastName() . ' ' . $this->faker->firstName()
        ];
    }
}
