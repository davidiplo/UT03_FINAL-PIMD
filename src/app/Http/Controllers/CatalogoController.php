<?php

namespace App\Http\Controllers;

use App\Models\Libro;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    /**
     * Muestra el catálogo público de libros.
     * Accesible sin autenticación (sin middleware 'auth').
     */
    public function index(Request $request)
    {
        $libros = Libro::query()
            ->when($request->filled('titulo'), fn($q) => $q->where('titulo', 'like', '%' . $request->titulo . '%'))
            ->when($request->filled('autor'),  fn($q) => $q->where('autor',  'like', '%' . $request->autor  . '%'))
            ->orderBy('titulo')
            ->paginate(12)
            ->withQueryString();

        // Si la petición es AJAX (fetch desde JS), devolvemos JSON
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'libros' => $libros->map(fn($l) => [
                    'titulo'    => $l->titulo,
                    'autor'     => $l->autor,
                    'isbn'      => $l->isbn,
                    'stock'     => $l->stock,
                    'disponible'=> $l->stock > 0,
                    'portada'   => $l->portada ? asset('storage/' . $l->portada) : null,
                ]),
                'total'       => $libros->total(),
                'current_page'=> $libros->currentPage(),
                'last_page'   => $libros->lastPage(),
            ]);
        }

        return view('catalogo.index', compact('libros'));
    }
}
