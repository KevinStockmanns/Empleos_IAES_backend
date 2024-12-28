<?php

use App\Enums\DisponibilidadEnum;
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
        Schema::create('perfil_profesional', function (Blueprint $table) {
            $table->id();
            $table->string('cargo')->nullable();
            $table->text('carta_presentacion')->nullable();
            $table->string('cv')->nullable();
            $table->string('disponibilidad', 50)->nullable();
            $table->boolean('disponibilidad_mudanza')->default(false);
            $table->foreignId('usuario_id')->constrained('usuarios');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfil_profesional');
    }
};
