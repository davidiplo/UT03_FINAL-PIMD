<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    use HasFactory;

    // Campos que permitimos rellenar masivamente
    protected $fillable = ['isbn', 'titulo', 'autor', 'stock', 'portada'];

    // Relación: Un libro puede tener muchos préstamos
    public function prestamos()
    {
        return $this->hasMany(Prestamo::class);
    }
}