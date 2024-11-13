<?php

namespace Database\Seeders;

use App\Enums\RolEnum;
use App\Models\Rol;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (RolEnum::cases() as $rol) {
            Rol::firstOrCreate(['nombre' => $rol->value]);
        }
    }
}
