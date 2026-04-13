<x-guest-layout>
    @if ($errors->any())
        <div class="alert alert-danger mb-3 p-2 text-center small">
            <i class="bi bi-exclamation-circle"></i> Credenciales incorrectas.
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label for="email" class="form-label fw-bold">Correo Electrónico</label>
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                <input type="email" name="email" id="email" class="form-control" placeholder="nombre@ejemplo.com" value="{{ old('email') }}" required autofocus>
            </div>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label fw-bold">Contraseña</label>
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-key"></i></span>
                <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                <label class="form-check-label small text-muted" for="remember_me">
                    Recordarme
                </label>
            </div>
            
            @if (Route::has('password.request'))
                <a class="text-decoration-none small" href="{{ route('password.request') }}">
                    ¿Olvidaste la contraseña?
                </a>
            @endif
        </div>

        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                Entrar <i class="bi bi-box-arrow-in-right"></i>
            </button>
        </div>
    </form>
</x-guest-layout>