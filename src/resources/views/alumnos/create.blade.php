@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Registrar Alumno</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('alumnos.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">NIE</label>
                        <input type="text" name="nie" class="form-control" value="{{ old('nie') }}" required maxlength="9">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" name="nombre" class="form-control" value="{{ old('nombre') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Apellidos</label>
                        <input type="text" name="apellidos" class="form-control" value="{{ old('apellidos') }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email (Opcional)</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('alumnos.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection