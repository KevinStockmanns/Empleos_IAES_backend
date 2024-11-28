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
        Schema::create('titulos_detalles', function (Blueprint $table) {
            $table->id();
            $table->date('fecha_inicio');
            $table->date("fecha_final")->nullable();
            $table->decimal('promedio', 4,2)->nullable();
            $table->string('tipo');
            $table->text('descripcion')->nullable();
            $table->foreignId('titulo_id')->constrained('titulos');
            $table->foreignId('usuario_id')->constrained('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('titulos_detalles');
    }
};
