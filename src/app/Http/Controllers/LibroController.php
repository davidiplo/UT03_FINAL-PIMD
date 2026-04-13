<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use App\Models\Movimiento; // <--- NUEVO
use Illuminate\Support\Facades\Auth; // <--- NUEVO
use Illuminate\Http\Request;

class LibroController extends Controller
{
    public function index()
    {
        $libros = Libro::paginate(10);
        return view('libros.index', compact('libros'));
    }

    public function create()
    {
        return view('libros.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'isbn' => 'required|size:13|unique:libros',
            'titulo' => 'required|max:255',
            'autor' => 'required|max:255',
            'stock' => 'required|integer|min:0'
        ]);

        // Guardamos el libro en una variable para usar sus datos en el log
        $libro = Libro::create($request->all());

        // --- REGISTRO MOVIMIENTO ---
        Movimiento::create([
            'user_id' => Auth::id(),
            'accion' => 'Crear Libro',
            'descripcion' => "Título: {$libro->titulo} (ISBN: {$libro->isbn})"
        ]);

        return redirect()->route('libros.index')
                         ->with('mensaje', '¡Libro registrado correctamente!');
    }

    public function edit(Libro $libro)
    {
        return view('libros.edit', compact('libro'));
    }

    public function update(Request $request, Libro $libro)
    {
        $request->validate([
            'isbn' => 'required|size:13|unique:libros,isbn,' . $libro->id,
            'titulo' => 'required|max:255',
            'stock' => 'required|integer|min:0'
        ]);

        $libro->update($request->all());

        // --- REGISTRO MOVIMIENTO ---
        Movimiento::create([
            'user_id' => Auth::id(),
            'accion' => 'Editar Libro',
            'descripcion' => "Actualizó el libro: {$libro->titulo}"
        ]);

        return redirect()->route('libros.index')
                         ->with('mensaje', 'Libro actualizado correctamente');
    }

    public function destroy(Libro $libro)
    {
        // 1. Comprobamos si tiene préstamos asociados (historial)
        if ($libro->prestamos()->exists()) {
            return redirect()->route('libros.index')
                             ->with('error', 'No se puede eliminar este libro porque tiene préstamos registrados. Bórralos primero.');
        }

        // 2. Guardamos el nombre antes de borrar para el Log
        $titulo = $libro->titulo; 
        
        // 3. Borramos
        $libro->delete();

        // 4. REGISTRO MOVIMIENTO (Log)
        Movimiento::create([
            'user_id' => Auth::id(),
            'accion' => 'Borrar Libro',
            'descripcion' => "Eliminó el libro: {$titulo}"
        ]);

        return redirect()->route('libros.index')->with('mensaje', 'Libro eliminado correctamente');
    }
}