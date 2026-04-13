@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Registrar Nuevo Préstamo</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('prestamos.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">Alumno</label>
                        <select name="alumno_id" class="form-select" required>
                            <option value="">-- Seleccionar Alumno --</option>
                            @foreach($alumnos as $alumno)
                                <option value="{{ $alumno->id }}">{{ $alumno->nombre }} {{ $alumno->apellidos }} ({{ $alumno->nie }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Libro (Solo con Stock disponible)</label>
                        <select name="libro_id" class="form-select" required>
                            <option value="">-- Seleccionar Libro --</option>
                            @foreach($libros as $libro)
                                <option value="{{ $libro->id }}">{{ $libro->titulo }} (Stock: {{ $libro->stock }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Fecha de Préstamo</label>
                        <input type="date" name="fecha_prestamo" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('prestamos.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Realizar Préstamo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection