<?php

namespace Database\Seeders;

use App\Models\Pais;
use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $argentina = Pais::where('nombre', 'Argentina')->first();
        if ($argentina) {
            $provincias = [
                ['nombre' => 'Buenos Aires', 'pais_id' => $argentina->id],
                ['nombre' => 'Córdoba', 'pais_id' => $argentina->id],
                ['nombre' => 'Santa Fe', 'pais_id' => $argentina->id],
                ['nombre' => 'Mendoza', 'pais_id' => $argentina->id],
                ['nombre' => 'Misiones', 'pais_id' => $argentina->id],
            ];

            foreach ($provincias as $provincia) {
                DB::table('provincias')->updateOrInsert(
                    ['nombre' => $provincia['nombre'], 'pais_id' => $argentina->id],
                    ['nombre' => $provincia['nombre'], 'pais_id' => $argentina->id]
                );
            }
        }
    }
}
