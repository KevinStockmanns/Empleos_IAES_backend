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
        Schema::create('pasantias_usuarios', function (Blueprint $table) {
            $table->id();

            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('pasantia_id')->constrained('pasantias')->onDelete('cascade');
            $table->decimal("nota",4,2)->nullable();
            
            $table->unique(['usuario_id', 'pasantia_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasantias_usuarios');
    }
};
