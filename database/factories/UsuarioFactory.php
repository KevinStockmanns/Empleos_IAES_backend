<?php

namespace Database\Factories;

use App\Enums\EstadoCivilEnum;
use App\Enums\EstadoUsuarioEnum;
use App\Enums\GeneroEnum;
use App\Models\Rol;
use Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Usuario>
 */
class UsuarioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => $this->faker->firstName(),
            'apellido' => $this->faker->lastName(),
            'fecha_nacimiento' => $this->faker->date(),
            'correo' => $this->faker->unique()->safeEmail(),
            'clave' => Hash::make('12345678'), // Contraseña encriptada
            'rol_id' => Rol::inRandomOrder()->first()?->id ?? 1, // Obtiene un rol aleatorio o usa 1 por defecto
            'estado' => EstadoUsuarioEnum::ALTA->value,
            'estado_civil' => $this->faker->randomElement([
                EstadoCivilEnum::SOLTERO->value, 
                EstadoCivilEnum::CASADO->value
            ]),
            'genero' => $this->faker->randomElement([
                GeneroEnum::MASCULINO->value, 
                GeneroEnum::FEMENINO->value
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
