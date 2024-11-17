<?php

namespace Database\Seeders;

use App\Enums\EstadoUsuarioEnum;
use App\Enums\RolEnum;
use App\Models\Rol;
use App\Models\Usuario;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UsuarioSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if(!Usuario::where('correo', 'admin@gmail.com')->exists()){
            $rol = Rol::where([
                'nombre'=>RolEnum::DEV->value
            ])->first();
    
            Usuario::create([
                'nombre'=>'SysAdmin',
                'apellido'=>'SysAdmin',
                'correo'=>'admin@gmail.com',
                'clave'=>Hash::make('adminadmin'),
                'rol_id'=> $rol->id,
                'estado'=> EstadoUsuarioEnum::PRIVADO->value
            ]);
        }
    }
}
