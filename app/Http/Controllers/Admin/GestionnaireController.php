<?php
// app/Http/Controllers/Admin/GestionnaireController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class GestionnaireController extends Controller
{
    public function index()
    {
        $gestionnaires = User::role('gestionnaire')
            ->withCount(['affectations', 'clients'])
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.gestionnaires.index', compact('gestionnaires'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)->letters()->numbers()],
        ], [
            'name.required'     => 'Le nom est obligatoire.',
            'email.required'    => 'L\'email est obligatoire.',
            'email.unique'      => 'Cet email est déjà utilisé.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed'=> 'La confirmation du mot de passe ne correspond pas.',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);
        $user->assignRole('gestionnaire');

        return redirect()->route('admin.dashboard')
            ->with('success', "Gestionnaire {$user->name} créé avec succès.");
    }

    public function destroy(User $gestionnaire)
    {
        if ($gestionnaire->hasRole('admin')) {
            return back()->with('error', 'Impossible de supprimer un administrateur.');
        }
        $gestionnaire->delete();
        return redirect()->route('admin.dashboard')
            ->with('success', 'Gestionnaire supprimé.');
    }
}