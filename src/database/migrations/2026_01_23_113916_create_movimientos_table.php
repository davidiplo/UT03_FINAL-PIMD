<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void {
    Schema::create('movimientos', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // ¿Quién lo hizo?
        $table->string('accion'); // Ej: "Creó Libro", "Eliminó Alumno"
        $table->text('descripcion')->nullable(); // Ej: "Título: El Quijote"
        $table->timestamps(); // Cuándo ocurrió
    });
}
    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};
