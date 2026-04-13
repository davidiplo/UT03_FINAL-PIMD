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
    Schema::create('prestamos', function (Blueprint $table) {
        $table->id();
        // Relaciones (Foreign Keys)
        $table->foreignId('libro_id')->constrained('libros')->onDelete('restrict');
        $table->foreignId('alumno_id')->constrained('alumnos')->onDelete('restrict');
        
        $table->date('fecha_prestamo');
        $table->date('fecha_devolucion')->nullable(); // Nullable porque al principio no se ha devuelto
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prestamos');
    }
};
