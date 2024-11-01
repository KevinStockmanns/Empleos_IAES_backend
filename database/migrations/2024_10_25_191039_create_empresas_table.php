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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',100)->nullable(false);
            $table->string('cuil_cuit')->nullable();
            $table->string('referente')->nullable();
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios');
            $table->foreignId('direccion_id')->nullable()->constrained('direcciones');
            $table->foreignId('horario_id')->constrained('horarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
