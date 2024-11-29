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
            'nombre'=>'Tecnico Superior en Analista de Sistemas de Computación',
            'institucion'=> 'Instituto Argentino de Estudios Superiores',
            'alias'=>'IAES',
            'visible'=>true
        ]);
        Titulo::firstOrCreate([
            'nombre'=>'Tecnico Superior en Administración de Empresas',
            'institucion'=> 'Instituto Argentino de Estudios Superiores',
            'alias'=>'IAES',
            'visible'=>true
        ]);
        Titulo::firstOrCreate([
            'nombre'=>'Tecnico Superior en Gestion de Recursos Humanos',
            'institucion'=> 'Instituto Argentino de Estudios Superiores',
            'alias'=>'IAES',
            'visible'=>true
        ]);
        Titulo::firstOrCreate([
            'nombre'=>'Tecnico Superior en Turismo y Gesstión Hotelera',
            'institucion'=> 'Instituto Argentino de Estudios Superiores',
            'alias'=>'IAES',
            'visible'=>true
        ]);
        Titulo::firstOrCreate([
            'nombre'=>'Tecnico Superior en Regimen Aduanero',
            'institucion'=> 'Instituto Argentino de Estudios Superiores',
            'alias'=>'IAES',
            'visible'=>true
        ]);
    }
}
