<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
    use HasFactory;

    protected $fillable = ['nie', 'nombre', 'apellidos', 'email'];

    // Relación: Un alumno puede tener muchos préstamos
    public function prestamos()
    {
        return $this->hasMany(Prestamo::class);
    }
}