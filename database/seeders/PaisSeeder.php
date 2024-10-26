<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaisSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('paises')->updateOrInsert(
            ['nombre' => 'Argentina'], // Condición para verificar si ya existe
            ['nombre' => 'Argentina']  // Datos a insertar si no existe
        );
    }
}
