<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'accion', 'descripcion'];

    // Relación: Un movimiento lo hace un Usuario
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}