<?php

namespace App\Http\Controllers;

use App\Models\Movimiento;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        // Verificamos si es admin (Seguridad extra aparte del middleware)
        if (auth()->user()->role !== 'admin') {
            abort(403, 'No tienes permiso para estar aquí.');
        }

        // Sacamos los movimientos ordenados por fecha
        $movimientos = Movimiento::with('user')->orderByDesc('created_at')->paginate(15);

        return view('admin.movimientos', compact('movimientos'));
    }
}