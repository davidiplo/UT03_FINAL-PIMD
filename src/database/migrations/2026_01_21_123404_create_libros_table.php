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
    Schema::create('libros', function (Blueprint $table) {
        $table->id();
        $table->string('isbn', 13)->unique(); // ISBN único
        $table->string('titulo');
        $table->string('autor');
        $table->integer('stock')->default(0);
        $table->string('portada')->nullable(); // Para la imagen
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('libros');
    }
};
