@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-primary">
            <div class="card-header bg-primary text-white">
                <h4 class="mb-0">Registrar Nuevo Usuario</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('users.store') }}" method="POST" autocomplete="off">
                    @csrf
                    
                    <input type="text" style="display:none">
                    <input type="password" style="display:none">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Nombre Completo</label>
                        <input type="text" 
                               name="name" 
                               class="form-control @error('name') is-invalid @enderror" 
                               placeholder="Ej: Pedro Martínez" 
                               value="{{ old('name') }}" 
                               autocomplete="off"
                               required>
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Correo Electrónico</label>
                        <input type="email" 
                               name="email" 
                               class="form-control @error('email') is-invalid @enderror" 
                               placeholder="usuario@ejemplo.com" 
                               value="{{ old('email') }}" 
                               autocomplete="new-password" 
                               required>
                        @error('email')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Contraseña</label>
                        <input type="password" 
                               name="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               placeholder="Escribe una contraseña nueva..." 
                               autocomplete="new-password" 
                               required>
                        <div class="form-text text-muted">Mínimo 8 caracteres.</div>
                        
                        @error('password')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="alert alert-info small">
                        <i class="bi bi-info-circle"></i> Este usuario se creará con el rol de <strong>Usuario Bibliotecario</strong>.
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-primary">Guardar Usuario</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection