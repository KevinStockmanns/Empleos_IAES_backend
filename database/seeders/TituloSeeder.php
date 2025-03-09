<?php

namespace Database\Seeders;

use App\Models\Titulo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TituloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Titulo::firstOrCreate([
            'nombre'=>'Técnico Superior en Analista de Sistemas de Computación',
            'institucion'=> 'Instituto Argentino de Estudios Superiores',
            'alias'=>'IAES',
            'visible'=>true
        ]);
        Titulo::firstOrCreate([
            'nombre'=>'Técnico Superior en Administración de Empresas',
            'institucion'=> 'Instituto Argentino de Estudios Superiores',
            'alias'=>'IAES',
            'visible'=>true
        ]);
        Titulo::firstOrCreate([
            'nombre'=>'Técnico Superior en Gestión de Recursos Humanos',
            'institucion'=> 'Instituto Argentino de Estudios Superiores',
            'alias'=>'IAES',
            'visible'=>true
        ]);
        Titulo::firstOrCreate([
            'nombre'=>'Técnico Superior en Turismo y Gestión Hotelera',
            'institucion'=> 'Instituto Argentino de Estudios Superiores',
            'alias'=>'IAES',
            'visible'=>true
        ]);
        Titulo::firstOrCreate([
            'nombre'=>'Técnico Superior en Régimen Aduanero',
            'institucion'=> 'Instituto Argentino de Estudios Superiores',
            'alias'=>'IAES',
            'visible'=>true
        ]);
    }
}
