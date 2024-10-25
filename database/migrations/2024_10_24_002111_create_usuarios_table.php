<?php

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
            $table->string('dni',20)->unique();
            $table->string('correo')->unique();
            $table->dateTime('nacimiento');
            $table->string('clave');
            $table->string('foto_perfil');
            $table->boolean('estado')->default(true);
            $table->foreignId("rol_id")->constrained('roles');
            $table->foreignId('habilidad_id')->constrained('habilidades');
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
