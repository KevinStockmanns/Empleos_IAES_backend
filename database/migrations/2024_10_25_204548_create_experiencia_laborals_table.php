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
        Schema::create('experiencias_laborales', function (Blueprint $table) {
            $table->id();
            $table->string('puesto');
            $table->string('empresa');
            $table->date('fecha_inicio');
            $table->date('fecha_terminacion')->nullable();
            $table->text('descripcion')->nullable();
            
            $table->foreignId('usuario_id')
                ->constrained('usuarios')
                ->onDelete('cascade');

            $table->foreignId('empresa_id')
                ->nullable()
                ->constrained('empresas')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('experiencias_laborales');
    }
};
