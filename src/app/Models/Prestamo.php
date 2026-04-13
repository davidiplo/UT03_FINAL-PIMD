<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    use HasFactory;

    // Campos que permitimos rellenar
    protected $fillable = [
        'libro_id',
        'alumno_id',
        'fecha_prestamo',
        'fecha_devolucion',
        'es_moroso'
    ];

    // Relación: Un préstamo pertenece a un Libro
    public function libro()
    {
        return $this->belongsTo(Libro::class);
    }

    // Relación: Un préstamo pertenece a un Alumno
    public function alumno()
    {
        return $this->belongsTo(Alumno::class);
    }
}