@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Inventario de Libros</h1>
    <a href="{{ route('libros.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Nuevo Libro
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ISBN</th>
                    <th>Título</th>
                    <th>Autor</th>
                    <th>Stock</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($libros as $libro)
                    <tr>
                        <td>{{ $libro->isbn }}</td>
                        <td>{{ $libro->titulo }}</td>
                        <td>{{ $libro->autor }}</td>
                        <td>
                            <span class="badge bg-{{ $libro->stock > 0 ? 'success' : 'danger' }}">
                                {{ $libro->stock }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('libros.edit', $libro) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            
                            <form action="{{ route('libros.destroy', $libro) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que quieres borrar este libro?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">
                            No hay libros registrados todavía.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        
        <div class="d-flex justify-content-center mt-3">
            {{ $libros->links() }}
        </div>
    </div>
</div>
@endsection