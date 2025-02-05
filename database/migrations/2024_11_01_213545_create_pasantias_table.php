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
            $table->date('fecha_inicio');
            $table->date('fecha_final')->nullable();
            $table->decimal("nota",4,2)->nullable();
            $table->foreignId('usuairo_id')->nullable(false)->constrained('usuarios');
            $table->foreignId('empresa_id')->nullable(false)->constrained('empresas');
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
