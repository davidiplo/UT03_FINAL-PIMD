@extends('layouts.app')

@section('content')
<div class="mb-4 text-danger">
    <h1><i class="bi bi-slash-circle"></i> Lista de Morosos e Incidentes</h1>
    <p class="text-muted">Aquí aparecen los préstamos marcados como conflictivos.</p>
</div>

<div class="card shadow-sm border-danger">
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead class="table-danger">
                <tr>
                    <th>Alumno</th>
                    <th>Libro</th>
                    <th>Estado Original</th>
                    <th>Fecha Préstamo</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($morosos as $prestamo)
                    <tr>
                        <td class="fw-bold text-danger">{{ $prestamo->alumno->nombre }} {{ $prestamo->alumno->apellidos }}</td>
                        <td>{{ $prestamo->libro->titulo }}</td>
                        <td>
                            @if($prestamo->fecha_devolucion)
                                <span class="badge bg-secondary">Libro devuelto (pero con incidencia)</span>
                            @else
                                <span class="badge bg-danger">Libro NO devuelto</span>
                            @endif
                        </td>
                        <td>{{ $prestamo->fecha_prestamo }}</td>
                        
                        <td class="text-end">
                            <form action="{{ route('morosos.destroy', $prestamo) }}" method="POST">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline-success btn-sm" onclick="return confirm('¿Perdonar a este usuario y devolverlo al historial normal?')">
                                    <i class="bi bi-check-lg"></i> Solucionado
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-success">
                            <i class="bi bi-emoji-sunglasses fs-1"></i><br>
                            <strong>¡Todo limpio!</strong> No hay morosos en este momento.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        {{ $morosos->links() }}
    </div>
</div>
@endsection