<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // HEMOS QUITADO EL __CONSTRUCT QUE DABA ERROR

    // Listar usuarios (Solo Admin)
    public function index()
    {
        // Seguridad manual: Si no es admin, fuera.
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acceso denegado. Solo administradores.');
        }

        $users = User::where('role', 'user')->paginate(10);
        return view('users.index', compact('users'));
    }

    // Formulario de edición (Solo Admin)
    public function edit(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acceso denegado.');
        }

        return view('users.edit', compact('user'));
    }

    // Guardar cambios (Solo Admin)
    public function update(Request $request, User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acceso denegado.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|min:8',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('mensaje', 'Usuario actualizado correctamente.');
    }

    // Eliminar usuario (Solo Admin)
    public function destroy(User $user)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acceso denegado.');
        }

        $user->delete();
        return redirect()->route('users.index')->with('mensaje', 'Usuario eliminado del sistema.');
    }
    // --- NUEVO: Formulario de crear usuario ---
    public function create()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acceso denegado.');
        }
        return view('users.create');
    }

    // --- NUEVO: Guardar usuario nuevo ---
    public function store(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Acceso denegado.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // ¡Importante encriptar!
            'role' => 'user', // Por defecto creamos usuarios normales
        ]);

        return redirect()->route('users.index')->with('mensaje', 'Nuevo usuario registrado correctamente.');
    }
}