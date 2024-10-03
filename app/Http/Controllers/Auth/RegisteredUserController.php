<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Entite;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        $roles = Role::all();
        $entites = Entite::all();
        return view("auth.register", [
            'roles' => $roles,
            'entites' => $entites,
        ]);
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'last_name' => ['required', 'string', 'max:255'],
            'first_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'role_id' => ['required', 'integer'],
            // 'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $password = strtoupper(substr($request->first_name, 0, 1)) . strtolower($request->last_name) . date('Y');

        $user = User::create([
            'last_name' => $request->last_name,
            'first_name'=> $request->first_name,
            'phone'=> $request->phone,
            'email' => $request->email,
            'role_id'=> $request->role_id,
            'password' => Hash::make($password),
        ]);
        return redirect(route('profile.index', absolute: false))->with('status','Utilisateur créé avec succès');
    }
}
