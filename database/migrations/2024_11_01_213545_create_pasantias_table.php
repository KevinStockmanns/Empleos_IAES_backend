<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('pasantias', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 40);
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_final')->nullable();
            // $table->foreignId('usuario_id')->nullable()->constrained('usuarios');
            $table->foreignId('empresa_id')->nullable()->constrained('empresas');
            // $table->string('empresa')->nullable();
            $table->text('desc')->nullable();
            $table->text('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasantias');
    }
};
