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
        Schema::create('licencias_conducir', function (Blueprint $table) {
            $table->id();
            $table->enum('categoria', ['A1', 'A2','A3','B1','B2','C1','C2','C3','D1','D2','D3','D4','E1','E2','F','G1','G2','G3','']);
            $table->boolean('vehiculo_propio');
            $table->foreignId('usuario_id')->constrained('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licencia_conducirs');
    }
};
