<x-guest-layout>
    <div class="mb-4 text-muted small">
        <i class="bi bi-info-circle-fill text-primary"></i> 
        ¿Olvidaste tu contraseña? No pasa nada. Dinos tu correo electrónico y te enviaremos un enlace para que elijas una nueva.
    </div>

    @if (session('status'))
        <div class="alert alert-success mb-3 p-2 small">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger mb-3 p-2 small">
            Error: Revisa el correo introducido.
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label fw-bold">Correo Electrónico</label>
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" id="email" class="form-control" placeholder="nombre@ejemplo.com" value="{{ old('email') }}" required autofocus>
            </div>
        </div>

        <div class="d-grid gap-2 mt-4">
            <button type="submit" class="btn btn-primary shadow-sm">
                Enviar enlace de recuperación <i class="bi bi-send"></i>
            </button>
            
            <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm border-0">
                <i class="bi bi-arrow-left"></i> Volver al login
            </a>
        </div>
    </form>
</x-guest-layout>
