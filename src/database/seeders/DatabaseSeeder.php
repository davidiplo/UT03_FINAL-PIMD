<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear al JEFE SUPREMO (Admin)
        User::create([
            'name' => 'Jefe Supremo',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Crear a MARIA (Bibliotecaria)
        User::create([
            'name' => 'Maria',
            'email' => 'maria@test.com',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);

        // 3. Crear algunos libros y alumnos de prueba (Opcional, usando Factories)
        // \App\Models\Libro::factory(10)->create();
        // \App\Models\Alumno::factory(5)->create();
    }
}