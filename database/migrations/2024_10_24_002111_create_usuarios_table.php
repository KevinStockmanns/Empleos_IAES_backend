<?php

use App\Enums\EstadoUsuarioEnum;
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
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre',100);
            $table->string('apellido',50);
            $table->string('dni',20)->unique();
            $table->string('correo')->unique();
            $table->date('fecha_nacimiento')->nullable(true);
            $table->string('clave');
            $table->enum('estado',allowed:  array_column(EstadoUsuarioEnum::cases(), 'value'))->default(EstadoUsuarioEnum::PRIVADO->value);
            $table->foreignId("rol_id")->constrained('roles');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
