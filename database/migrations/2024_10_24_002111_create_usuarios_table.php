<?php

use App\Enums\EstadoUsuarioEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',100);
            $table->string('apellido',50);
            $table->string('dni',20)->nullable()->unique();
            $table->string('correo')->unique();
            $table->date('fecha_nacimiento')->nullable(true);
            $table->string('clave');
            $table->string('estado', 50);
            $table->string('foto_perfil')->nullable()->default(null);
            $table->foreignId("rol_id")->constrained('roles');
            $table->foreignId('direccion_id')->nullable(true)->default(null)->constrained('direcciones');
            $table->foreignId('contacto_id')->nullable()->constrained('contactos');
            $table->datetime('ultimo_inicio')->nullable()->default(null);
            $table->string('genero');
            $table->string('estado_civil');
            $table->timestamps();
            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
