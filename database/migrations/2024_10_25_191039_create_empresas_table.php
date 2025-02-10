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
            $table->string('cuil_cuit',20)->nullable();
            $table->string('referente',50)->nullable();
            $table->string('imagen')->nullable();
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios');
            $table->foreignId('direccion_id')->nullable()->constrained('direcciones');
            $table->timestamps();
            $table->softDeletes();
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
