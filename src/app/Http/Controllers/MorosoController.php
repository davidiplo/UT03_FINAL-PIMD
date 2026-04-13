<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use Illuminate\Http\Request;

class MorosoController extends Controller
{
    // Mostrar SOLO la lista de morosos
    public function index()
    {
        $morosos = Prestamo::with(['libro', 'alumno'])
                    ->where('es_moroso', true)
                    ->orderByDesc('updated_at')
                    ->paginate(10);

        return view('morosos.index', compact('morosos'));
    }

    // Sacar de la lista (Perdonar / Solucionado)
    public function destroy(Prestamo $moroso)
    {
        // Le quitamos la marca de moroso
        $moroso->update(['es_moroso' => false]);

        return redirect()->route('morosos.index')->with('mensaje', 'Usuario sacado de la lista de morosos.');
    }
}