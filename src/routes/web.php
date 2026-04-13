<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LibroController;
use App\Http\Controllers\AlumnoController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\MorosoController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;

// Página de bienvenida (puedes dejar la de Breeze o redirigir al login)
Route::get('/', function () {
    return redirect()->route('login');
});

// GRUPO PROTEGIDO: Solo usuarios logueados pueden entrar aquí
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return redirect()->route('libros.index');
    })->name('dashboard');
    // --- RUTAS DE LA APP (Tus rutas de siempre) ---
    Route::resource('libros', LibroController::class);
    Route::resource('alumnos', AlumnoController::class);
    
    Route::resource('prestamos', PrestamoController::class)->only(['index', 'create', 'store', 'destroy']);
    Route::patch('/prestamos/{prestamo}/devolver', [PrestamoController::class, 'devolver'])->name('prestamos.devolver');
    Route::patch('/prestamos/{prestamo}/moroso', [PrestamoController::class, 'hacerMoroso'])->name('prestamos.hacer_moroso');
    
    Route::resource('morosos', MorosoController::class)->only(['index', 'destroy']);

    // --- RUTA SOLO PARA ADMIN ---
    Route::get('/admin/logs', [AdminController::class, 'index'])->name('admin.logs');
    Route::resource('users', UserController::class)->except(['show']);
});

require __DIR__.'/auth.php'; // Esto lo pone Breeze