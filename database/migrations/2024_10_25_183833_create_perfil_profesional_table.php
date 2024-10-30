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
            $table->string('cargo');
            $table->text('carta_presentación');
            $table->string('cv');
            $table->enum('disponibilidad', array_column(DisponibilidadEnum::cases(), 'value'));
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
