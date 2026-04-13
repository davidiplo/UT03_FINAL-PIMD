@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Gestión de Préstamos</h1>
    <a href="{{ route('prestamos.create') }}" class="btn btn-primary">
        <i class="bi bi-journal-plus"></i> Nuevo Préstamo
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Estado</th>
                    <th>Libro</th>
                    <th>Alumno</th>
                    <th>Fecha Préstamo</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prestamos as $prestamo)
                    <tr>
                        <td>
                            @if($prestamo->fecha_devolucion)
                                <span class="badge bg-secondary">Devuelto ({{ \Carbon\Carbon::parse($prestamo->fecha_devolucion)->format('d/m') }})</span>
                            @else
                                <span class="badge bg-success">Activo</span>
                            @endif
                        </td>
                        <td>{{ $prestamo->libro->titulo }}</td>
                        <td>{{ $prestamo->alumno->nombre }} {{ $prestamo->alumno->apellidos }}</td>
                        <td>{{ \Carbon\Carbon::parse($prestamo->fecha_prestamo)->format('d/m/Y') }}</td>
                        
                        <td class="text-end">
                            <div class="btn-group" role="group">
                                
                                <form action="{{ route('prestamos.hacer_moroso', $prestamo) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Marcar como Moroso / Incidente" onclick="return confirm('¿Mover a este préstamo a la lista de MOROSOS?')">
                                        <i class="bi bi-exclamation-triangle-fill"></i>
                                    </button>
                                </form>

                                @if(!$prestamo->fecha_devolucion)
                                    <form action="{{ route('prestamos.devolver', $prestamo) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="btn btn-success btn-sm ms-1" onclick="return confirm('¿Confirmar devolución correcta del libro?')">
                                            Devolver <i class="bi bi-box-arrow-in-down"></i>
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('prestamos.destroy', $prestamo) }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm ms-1" title="Borrar registro del sistema" onclick="return confirm('¿Borrar este préstamo permanentemente?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-4">No hay préstamos activos ni historial reciente.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $prestamos->links() }}
    </div>
</div>
@endsection