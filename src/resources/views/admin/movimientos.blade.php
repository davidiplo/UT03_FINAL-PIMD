@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="text-primary"><i class="bi bi-shield-lock"></i> Auditoría de Movimientos</h1>
    <span class="badge bg-primary fs-6">Zona Admin</span>
</div>

<div class="card shadow">
    <div class="card-body">
        <table class="table table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Usuario</th>
                    <th>Acción</th>
                    <th>Detalles</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @foreach($movimientos as $log)
                    <tr>
                        <td class="fw-bold">{{ $log->user->name }}</td>
                        <td>
                            <span class="badge bg-{{ str_contains($log->accion, 'Borrar') ? 'danger' : (str_contains($log->accion, 'Crear') ? 'success' : 'warning') }}">
                                {{ $log->accion }}
                            </span>
                        </td>
                        <td>{{ $log->descripcion }}</td>
                        <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $movimientos->links() }}
    </div>
</div>
@endsection