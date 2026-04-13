<?php

namespace App\Http\Controllers;

use App\Models\Alumno;
use App\Models\Movimiento; // <--- NUEVO
use Illuminate\Support\Facades\Auth; // <--- NUEVO
use Illuminate\Http\Request;

class AlumnoController extends Controller
{
    public function index()
    {
        $alumnos = Alumno::paginate(10);
        return view('alumnos.index', compact('alumnos'));
    }

    public function create()
    {
        return view('alumnos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nie' => 'required|string|size:9|unique:alumnos',
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'nullable|email|max:255'
        ]);

        $alumno = Alumno::create($request->all());

        // --- REGISTRO MOVIMIENTO ---
        Movimiento::create([
            'user_id' => Auth::id(),
            'accion' => 'Crear Alumno',
            'descripcion' => "Nombre: {$alumno->nombre} {$alumno->apellidos} (NIE: {$alumno->nie})"
        ]);

        return redirect()->route('alumnos.index')
                         ->with('mensaje', 'Alumno registrado correctamente');
    }

    public function edit(Alumno $alumno)
    {
        return view('alumnos.edit', compact('alumno'));
    }

    public function update(Request $request, Alumno $alumno)
    {
        $request->validate([
            'nie' => 'required|string|size:9|unique:alumnos,nie,' . $alumno->id,
            'nombre' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'email' => 'nullable|email|max:255'
        ]);

        $alumno->update($request->all());

        // --- REGISTRO MOVIMIENTO ---
        Movimiento::create([
            'user_id' => Auth::id(),
            'accion' => 'Editar Alumno',
            'descripcion' => "Actualizó datos de: {$alumno->nombre} {$alumno->apellidos}"
        ]);

        return redirect()->route('alumnos.index')
                         ->with('mensaje', 'Datos del alumno actualizados');
    }

    public function destroy(Alumno $alumno)
    {
        $nombreCompleto = "{$alumno->nombre} {$alumno->apellidos}";
        
        try {
            $alumno->delete();

            // --- REGISTRO MOVIMIENTO ---
            Movimiento::create([
                'user_id' => Auth::id(),
                'accion' => 'Borrar Alumno',
                'descripcion' => "Eliminó al alumno: {$nombreCompleto}"
            ]);

            return redirect()->route('alumnos.index')->with('mensaje', 'Alumno eliminado');
        } catch (\Exception $e) {
            return redirect()->route('alumnos.index')->with('error', 'No se puede borrar este alumno porque tiene libros prestados.');
        }
    }
}