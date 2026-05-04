<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor Librvm — Catálogo Público</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        :root {
            --color-primary:   #1F3864;
            --color-secondary: #2E75B6;
            --color-success:   #198754;
            --color-danger:    #DC3545;
        }
        body { background: #f4f6f9; }

        /* ── Navbar ── */
        .navbar-librvm {
            background: var(--color-primary);
            padding: 0.85rem 1.5rem;
        }
        .navbar-librvm .navbar-brand {
            color: #fff;
            font-size: 1.4rem;
            font-weight: 700;
            letter-spacing: .5px;
        }
        .navbar-librvm .navbar-brand span { color: #f0c040; }

        /* ── Hero ── */
        .hero {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
            color: #fff;
            padding: 3.5rem 1rem 2.5rem;
            text-align: center;
        }
        .hero h1 { font-size: 2.2rem; font-weight: 700; }
        .hero p  { opacity: .85; margin-bottom: 1.8rem; }

        /* ── Search form ── */
        .search-card {
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.25);
            border-radius: 1rem;
            padding: 1.2rem 1.5rem;
            max-width: 680px;
            margin: 0 auto;
        }
        .search-card .form-control {
            border-radius: .6rem;
            border: none;
        }
        .btn-buscar {
            background: #f0c040;
            color: var(--color-primary);
            font-weight: 700;
            border: none;
            border-radius: .6rem;
            padding: .55rem 1.4rem;
            transition: background .2s;
        }
        .btn-buscar:hover { background: #e6b830; }

        /* ── Results ── */
        #resultados-area { padding: 2rem 0 3rem; }

        .libro-card {
            background: #fff;
            border-radius: .85rem;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,.08);
            transition: transform .2s, box-shadow .2s;
            height: 100%;
        }
        .libro-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 6px 20px rgba(0,0,0,.14);
        }
        .libro-portada {
            background: #e9ecef;
            height: 160px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #adb5bd;
            font-size: 3rem;
        }
        .libro-portada img { width: 100%; height: 100%; object-fit: cover; }
        .libro-body { padding: 1rem; }
        .libro-titulo { font-weight: 700; font-size: .95rem; margin-bottom: .2rem; color: #1a1a2e; }
        .libro-autor  { font-size: .82rem; color: #6c757d; margin-bottom: .6rem; }
        .libro-isbn   { font-size: .75rem; color: #adb5bd; }

        /* ── Spinner ── */
        #spinner { display: none; }
        #spinner.visible { display: flex; }

        /* ── Badge ── */
        .badge-disponible { background: var(--color-success); }
        .badge-agotado    { background: var(--color-danger);  }

        /* ── Footer ── */
        footer {
            background: var(--color-primary);
            color: rgba(255,255,255,.7);
            text-align: center;
            padding: 1rem;
            font-size: .82rem;
        }
    </style>
</head>
<body>

{{-- ── Navbar ── --}}
<nav class="navbar navbar-librvm">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <span class="navbar-brand">
            <i class="bi bi-book-half me-2"></i>Gestor <span>Librvm</span>
        </span>
        @auth
            <a href="{{ route('libros.index') }}" class="btn btn-sm btn-outline-light">
                <i class="bi bi-speedometer2 me-1"></i>Panel de gestión
            </a>
        @else
            <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light">
                <i class="bi bi-box-arrow-in-right me-1"></i>Acceso administrador
            </a>
        @endauth
    </div>
</nav>

{{-- ── Hero + Buscador ── --}}
<section class="hero">
    <h1><i class="bi bi-search me-2"></i>Catálogo de la Biblioteca</h1>
    <p>Consulta la disponibilidad de libros en tiempo real. No necesitas registrarte.</p>

    <div class="search-card">
        <div class="row g-2 align-items-center">
            <div class="col-12 col-md-5">
                <input id="inp-titulo" type="text"
                       class="form-control"
                       placeholder="🔍  Título..."
                       value="{{ request('titulo') }}">
            </div>
            <div class="col-12 col-md-5">
                <input id="inp-autor" type="text"
                       class="form-control"
                       placeholder="✍️  Autor..."
                       value="{{ request('autor') }}">
            </div>
            <div class="col-12 col-md-2">
                <button id="btn-buscar" class="btn btn-buscar w-100">
                    Buscar
                </button>
            </div>
        </div>
    </div>
</section>

{{-- ── Resultados ── --}}
<section id="resultados-area">
    <div class="container">

        {{-- Spinner de carga (AJAX) --}}
        <div id="spinner" class="justify-content-center mb-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Cargando...</span>
            </div>
        </div>

        {{-- Contador --}}
        <p id="contador" class="text-muted mb-3 text-center">
            Mostrando <strong id="n-libros">{{ $libros->total() }}</strong> libro(s)
        </p>

        {{-- Grid de tarjetas --}}
        <div id="grid-libros" class="row g-3">
            @foreach($libros as $libro)
            <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                <div class="libro-card">
                    <div class="libro-portada">
                        @if($libro->portada)
                            <img src="{{ asset('storage/' . $libro->portada) }}" alt="{{ $libro->titulo }}">
                        @else
                            <i class="bi bi-book"></i>
                        @endif
                    </div>
                    <div class="libro-body">
                        <div class="libro-titulo">{{ $libro->titulo }}</div>
                        <div class="libro-autor">{{ $libro->autor }}</div>
                        <div class="d-flex align-items-center justify-content-between mt-2">
                            <span class="libro-isbn">ISBN: {{ $libro->isbn }}</span>
                            @if($libro->stock > 0)
                                <span class="badge badge-disponible">Disponible</span>
                            @else
                                <span class="badge badge-agotado">Agotado</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Mensaje sin resultados --}}
        <div id="sin-resultados" class="text-center py-5 d-none">
            <i class="bi bi-search text-muted" style="font-size:3rem"></i>
            <p class="mt-3 text-muted">No se encontraron libros con esos criterios.</p>
        </div>

        {{-- Paginación (renderizado inicial SSR) --}}
        <div id="paginacion" class="d-flex justify-content-center mt-4">
            {{ $libros->links() }}
        </div>
    </div>
</section>

<footer>
    &copy; {{ date('Y') }} IES Arcipreste de Hita — Gestor Librvm
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
/**
 * Búsqueda asíncrona del catálogo público.
 * Usa fetch() para consultar la API JSON del CatalogoController
 * sin recargar la página (comunicación asíncrona).
 */

const inpTitulo  = document.getElementById('inp-titulo');
const inpAutor   = document.getElementById('inp-autor');
const btnBuscar  = document.getElementById('btn-buscar');
const grid       = document.getElementById('grid-libros');
const spinner    = document.getElementById('spinner');
const sinRes     = document.getElementById('sin-resultados');
const nLibros    = document.getElementById('n-libros');
const paginacion = document.getElementById('paginacion');

let debounceTimer;

/**
 * Construye una tarjeta de libro como HTML string a partir del objeto JSON.
 */
function buildCard(libro) {
    const portadaHtml = libro.portada
        ? `<img src="${libro.portada}" alt="${libro.titulo}">`
        : `<i class="bi bi-book"></i>`;

    const badge = libro.disponible
        ? `<span class="badge badge-disponible">Disponible</span>`
        : `<span class="badge badge-agotado">Agotado</span>`;

    return `
        <div class="col-6 col-md-4 col-lg-3 col-xl-2">
            <div class="libro-card">
                <div class="libro-portada">${portadaHtml}</div>
                <div class="libro-body">
                    <div class="libro-titulo">${libro.titulo}</div>
                    <div class="libro-autor">${libro.autor}</div>
                    <div class="d-flex align-items-center justify-content-between mt-2">
                        <span class="libro-isbn">ISBN: ${libro.isbn}</span>
                        ${badge}
                    </div>
                </div>
            </div>
        </div>`;
}

/**
 * Realiza la petición AJAX al servidor y actualiza el DOM con los resultados.
 */
async function buscar() {
    const titulo = inpTitulo.value.trim();
    const autor  = inpAutor.value.trim();

    // Mostramos spinner y ocultamos contenido previo
    spinner.classList.add('visible');
    grid.innerHTML = '';
    sinRes.classList.add('d-none');
    paginacion.innerHTML = '';

    try {
        const params = new URLSearchParams();
        if (titulo) params.append('titulo', titulo);
        if (autor)  params.append('autor',  autor);

        // Cabecera 'Accept: application/json' → el controlador devuelve JSON
        const res  = await fetch(`/catalogo?${params.toString()}`, {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
        });
        const data = await res.json();

        nLibros.textContent = data.total;

        if (data.libros.length === 0) {
            sinRes.classList.remove('d-none');
        } else {
            grid.innerHTML = data.libros.map(buildCard).join('');
        }
    } catch (err) {
        console.error('Error en la búsqueda:', err);
    } finally {
        spinner.classList.remove('visible');
    }
}

// Búsqueda al pulsar el botón
btnBuscar.addEventListener('click', buscar);

// Búsqueda con debounce al escribir (300 ms de espera)
[inpTitulo, inpAutor].forEach(inp => {
    inp.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(buscar, 300);
    });
    // También al pulsar Enter
    inp.addEventListener('keydown', e => {
        if (e.key === 'Enter') { clearTimeout(debounceTimer); buscar(); }
    });
});
</script>
</body>
</html>
