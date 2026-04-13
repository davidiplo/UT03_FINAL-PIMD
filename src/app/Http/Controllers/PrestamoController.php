<?php

namespace App\Http\Controllers;

use App\Models\Prestamo;
use App\Models\Libro;
use App\Models\Alumno;
use App\Models\Movimiento; // <--- NUEVO
use Illuminate\Support\Facades\Auth; // <--- NUEVO
use Illuminate\Http\Request;

class PrestamoController extends Controller
{
    public function index()
    {
        $prestamos = Prestamo::with(['libro', 'alumno'])
                    ->where('es_moroso', false)
                    ->orderByRaw('fecha_devolucion IS NOT NULL')
                    ->orderByDesc('created_at')
                    ->paginate(10);

        return view('prestamos.index', compact('prestamos'));
    }

    public function create()
    {
        $libros = Libro::where('stock', '>', 0)->get();
        $alumnos = Alumno::all();
        return view('prestamos.create', compact('libros', 'alumnos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'libro_id' => 'required|exists:libros,id',
            'alumno_id' => 'required|exists:alumnos,id',
            'fecha_prestamo' => 'required|date',
        ]);

        $libro = Libro::find($request->libro_id);
        if ($libro->stock < 1) {
            return back()->withErrors(['libro_id' => '¡No queda stock de este libro!']);
        }

        $prestamo = Prestamo::create([
            'libro_id' => $request->libro_id,
            'alumno_id' => $request->alumno_id,
            'fecha_prestamo' => $request->fecha_prestamo,
            'es_moroso' => false,
        ]);

        $libro->decrement('stock');

        // --- REGISTRO MOVIMIENTO ---
        Movimiento::create([
            'user_id' => Auth::id(),
            'accion' => 'Nuevo Préstamo',
            'descripcion' => "Libro: {$libro->titulo} -> Alumno: {$prestamo->alumno->nombre}"
        ]);

        return redirect()->route('prestamos.index')->with('mensaje', 'Préstamo realizado correctamente.');
    }
    public function destroy(Prestamo $prestamo)
    {
        // 1. Si el libro NO se había devuelto aún, recuperamos el stock
        if (!$prestamo->fecha_devolucion) {
            $prestamo->libro->increment('stock');
        }

        // 2. Guardamos datos para el Log
        $info = "Libro: {$prestamo->libro->titulo} - Alumno: {$prestamo->alumno->nombre}";

        // 3. Borramos el registro
        $prestamo->delete();

        // 4. LOG (Auditoría)
        Movimiento::create([
            'user_id' => Auth::id(),
            'accion' => 'Borrar Préstamo',
            'descripcion' => "Eliminó del historial: $info"
        ]);

        return back()->with('mensaje', 'Préstamo eliminado correctamente.');
    }
    public function devolver(Prestamo $prestamo)
    {
        if ($prestamo->fecha_devolucion) {
            return back()->with('error', 'Este libro ya fue devuelto.');
        }

        $prestamo->libro->increment('stock');
        $prestamo->update(['fecha_devolucion' => now()]);

        // --- REGISTRO MOVIMIENTO ---
        Movimiento::create([
            'user_id' => Auth::id(),
            'accion' => 'Devolución Libro',
            'descripcion' => "Devolvió: {$prestamo->libro->titulo} (Alumno: {$prestamo->alumno->nombre})"
        ]);

        return back()->with('mensaje', 'Libro devuelto. Stock recuperado.');
    }

    public function hacerMoroso(Prestamo $prestamo)
    {
        $prestamo->update(['es_moroso' => true]);
        
        // --- REGISTRO MOVIMIENTO ---
        Movimiento::create([
            'user_id' => Auth::id(),
            'accion' => 'Marcar Moroso',
            'descripcion' => "Incidencia con: {$prestamo->alumno->nombre} (Libro: {$prestamo->libro->titulo})"
        ]);

        return redirect()->route('prestamos.index')->with('error', 'El préstamo se ha movido a la Lista de Morosos.');
    }
}