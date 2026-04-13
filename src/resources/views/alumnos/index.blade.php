@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Listado de Alumnos</h1>
    <a href="{{ route('alumnos.create') }}" class="btn btn-primary">
        <i class="bi bi-person-plus"></i> Nuevo Alumno
    </a>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>NIE</th>
                    <th>Nombre</th>
                    <th>Apellidos</th>
                    <th>Email</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($alumnos as $alumno)
                    <tr>
                        <td><strong>{{ $alumno->nie }}</strong></td>
                        <td>{{ $alumno->nombre }}</td>
                        <td>{{ $alumno->apellidos }}</td>
                        <td>{{ $alumno->email ?? '-' }}</td>
                        <td class="text-end">
                            <a href="{{ route('alumnos.edit', $alumno) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('alumnos.destroy', $alumno) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Borrar alumno?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-4">No hay alumnos registrados.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $alumnos->links() }}
    </div>
</div>
@endsection