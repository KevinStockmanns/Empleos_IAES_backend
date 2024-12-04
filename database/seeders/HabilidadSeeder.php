<?php

namespace Database\Seeders;

use App\Enums\HabilidadEnum;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HabilidadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $habilidades = [
            // Lenguajes de Programación
            ['nombre' => 'Python', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'Java', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'JavaScript', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'C#', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'PHP', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'TypeScript', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'Ruby', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'Swift', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'Kotlin', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'Go', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],

            // Idiomas
            ['nombre' => 'Inglés', 'tipo' => HabilidadEnum::IDIOMA, 'visible' => true],
            ['nombre' => 'Español', 'tipo' => HabilidadEnum::IDIOMA, 'visible' => true],
            ['nombre' => 'Francés', 'tipo' => HabilidadEnum::IDIOMA, 'visible' => true],
            ['nombre' => 'Alemán', 'tipo' => HabilidadEnum::IDIOMA, 'visible' => true],
            ['nombre' => 'Portugués', 'tipo' => HabilidadEnum::IDIOMA, 'visible' => true],
            ['nombre' => 'Italiano', 'tipo' => HabilidadEnum::IDIOMA, 'visible' => true],
            ['nombre' => 'Chino', 'tipo' => HabilidadEnum::IDIOMA, 'visible' => true],

            // Aptitudes
            ['nombre' => 'Trabajo en Equipo', 'tipo' => HabilidadEnum::APTITUD, 'visible' => true],
            ['nombre' => 'Comunicación', 'tipo' => HabilidadEnum::APTITUD, 'visible' => true],
            ['nombre' => 'Liderazgo', 'tipo' => HabilidadEnum::APTITUD, 'visible' => true],
            ['nombre' => 'Resolución de Problemas', 'tipo' => HabilidadEnum::APTITUD, 'visible' => true],
            ['nombre' => 'Creatividad', 'tipo' => HabilidadEnum::APTITUD, 'visible' => true],
            ['nombre' => 'Pensamiento Crítico', 'tipo' => HabilidadEnum::APTITUD, 'visible' => true],
            ['nombre' => 'Adaptabilidad', 'tipo' => HabilidadEnum::APTITUD, 'visible' => true],
            ['nombre' => 'Gestión del Tiempo', 'tipo' => HabilidadEnum::APTITUD, 'visible' => true],

            // Herramientas
            ['nombre' => 'Git', 'tipo' => HabilidadEnum::HERRAMIENTA, 'visible' => true],
            ['nombre' => 'Docker', 'tipo' => HabilidadEnum::HERRAMIENTA, 'visible' => true],
            ['nombre' => 'Jenkins', 'tipo' => HabilidadEnum::HERRAMIENTA, 'visible' => true],
            ['nombre' => 'Jira', 'tipo' => HabilidadEnum::HERRAMIENTA, 'visible' => true],
            ['nombre' => 'Trello', 'tipo' => HabilidadEnum::HERRAMIENTA, 'visible' => true],
            ['nombre' => 'Visual Studio Code', 'tipo' => HabilidadEnum::HERRAMIENTA, 'visible' => true],
            ['nombre' => 'Postman', 'tipo' => HabilidadEnum::HERRAMIENTA, 'visible' => true],
            ['nombre' => 'Slack', 'tipo' => HabilidadEnum::HERRAMIENTA, 'visible' => true],
            ['nombre' => 'Microsoft Office', 'tipo' => HabilidadEnum::HERRAMIENTA, 'visible' => true],
            ['nombre' => 'Google Workspace', 'tipo' => HabilidadEnum::HERRAMIENTA, 'visible' => true],

           
        ];

        DB::table('habilidades')->insert($habilidades);
    }
}
