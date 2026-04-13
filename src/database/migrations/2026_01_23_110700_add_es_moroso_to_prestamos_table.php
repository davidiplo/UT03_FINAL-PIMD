<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::table('prestamos', function (Blueprint $table) {
        // Añadimos la columna booleana (Verdadero/Falso) con valor por defecto "Falso"
        $table->boolean('es_moroso')->default(false)->after('fecha_devolucion');
    });
}

public function down(): void
{
    Schema::table('prestamos', function (Blueprint $table) {
        $table->dropColumn('es_moroso');
    });
}
};