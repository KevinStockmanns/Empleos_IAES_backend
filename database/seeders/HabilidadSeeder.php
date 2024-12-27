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
            ['nombre' => 'TypeScript', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'C#', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'PHP', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'Ruby', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'Kotlin', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'Swift', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'Go', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'Rust', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'Dart', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
        
            // Frameworks y librerías
            ['nombre' => 'Angular', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'React', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'Vue.js', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'Svelte', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'Node.js', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'Express.js', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'Laravel', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'Symfony', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'Spring Boot', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'Flask', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'Django', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'ASP.NET Core', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'Ruby on Rails', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'Next.js', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'Nuxt.js', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
        
            // Tecnologías relacionadas
            ['nombre' => 'GraphQL', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'REST APIs', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'Docker', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'Sass', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'Webpack', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],
            ['nombre' => 'Vite', 'tipo' => HabilidadEnum::LENGUAJE_PROGRAMACION, 'visible' => true],

            // Idiomas
            ['nombre' => 'Español', 'tipo' => HabilidadEnum::IDIOMA, 'visible' => true],
            ['nombre' => 'Inglés', 'tipo' => HabilidadEnum::IDIOMA, 'visible' => true],
            ['nombre' => 'Portugués', 'tipo' => HabilidadEnum::IDIOMA, 'visible' => true],
            ['nombre' => 'Italiano', 'tipo' => HabilidadEnum::IDIOMA, 'visible' => true],
            ['nombre' => 'Francés', 'tipo' => HabilidadEnum::IDIOMA, 'visible' => true],
            ['nombre' => 'Alemán', 'tipo' => HabilidadEnum::IDIOMA, 'visible' => true],
            ['nombre' => 'Guaraní', 'tipo' => HabilidadEnum::IDIOMA, 'visible' => true],
            ['nombre' => 'Chino', 'tipo' => HabilidadEnum::IDIOMA, 'visible' => true],
            ['nombre' => 'Japonés', 'tipo' => HabilidadEnum::IDIOMA, 'visible' => true],
            ['nombre' => 'Coreano', 'tipo' => HabilidadEnum::IDIOMA, 'visible' => true],
            ['nombre' => 'Árabe', 'tipo' => HabilidadEnum::IDIOMA, 'visible' => true],

            // Aptitudes
            ['nombre' => 'Trabajo en Equipo', 'tipo' => HabilidadEnum::APTITUD, 'visible' => true],
            ['nombre' => 'Comunicación', 'tipo' => HabilidadEnum::APTITUD, 'visible' => true],
            ['nombre' => 'Liderazgo', 'tipo' => HabilidadEnum::APTITUD, 'visible' => true],
            ['nombre' => 'Resolución de Problemas', 'tipo' => HabilidadEnum::APTITUD, 'visible' => true],
            ['nombre' => 'Creatividad', 'tipo' => HabilidadEnum::APTITUD, 'visible' => true],
            ['nombre' => 'Pensamiento Crítico', 'tipo' => HabilidadEnum::APTITUD, 'visible' => true],
            ['nombre' => 'Adaptabilidad', 'tipo' => HabilidadEnum::APTITUD, 'visible' => true],
            ['nombre' => 'Gestión del Tiempo', 'tipo' => HabilidadEnum::APTITUD, 'visible' => true],

            // Herramientas de productividad
            ['nombre' => 'Microsoft Office', 'tipo' => HabilidadEnum::HERRAMIENTA, 'visible' => true],
            ['nombre' => 'Google Workspace', 'tipo' => HabilidadEnum::HERRAMIENTA, 'visible' => true],
            ['nombre' => 'Trello', 'tipo' => HabilidadEnum::HERRAMIENTA, 'visible' => true],
            ['nombre' => 'Microsoft Teams', 'tipo' => HabilidadEnum::HERRAMIENTA, 'visible' => true],
            ['nombre' => 'Zoom', 'tipo' => HabilidadEnum::HERRAMIENTA, 'visible' => true],

            // Herramientas de diseño
            ['nombre' => 'Adobe Photoshop', 'tipo' => HabilidadEnum::HERRAMIENTA, 'visible' => true],
            ['nombre' => 'Adobe Illustrator', 'tipo' => HabilidadEnum::HERRAMIENTA, 'visible' => true],
            ['nombre' => 'Canva', 'tipo' => HabilidadEnum::HERRAMIENTA, 'visible' => true],
            ['nombre' => 'Figma', 'tipo' => HabilidadEnum::HERRAMIENTA, 'visible' => true],
            ['nombre' => 'Sketch', 'tipo' => HabilidadEnum::HERRAMIENTA, 'visible' => true],

            // Herramientas de análisis de datos
            ['nombre' => 'Power BI', 'tipo' => HabilidadEnum::HERRAMIENTA, 'visible' => true],
            ['nombre' => 'Google Analytics', 'tipo' => HabilidadEnum::HERRAMIENTA, 'visible' => true],
            ['nombre' => 'Microsoft Excel', 'tipo' => HabilidadEnum::HERRAMIENTA, 'visible' => true],

            // Herramientas generales
            ['nombre' => 'Google Drive', 'tipo' => HabilidadEnum::HERRAMIENTA, 'visible' => true],
            ['nombre' => 'OneDrive', 'tipo' => HabilidadEnum::HERRAMIENTA, 'visible' => true],

           
        ];

        DB::table('habilidades')->insert($habilidades);
    }
}