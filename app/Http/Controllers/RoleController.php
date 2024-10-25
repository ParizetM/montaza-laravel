<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entite;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use App\Models\notification;
use Auth;
use Route;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($role = 0)
    {
        $role = Role::withTrashed()->findOrFail($role ?: 1);
        $roles = Role::withTrashed()->get();
        $entites = Entite::with(['roles' => function ($query) {
            $query->withTrashed(); // Inclure les rôles supprimés
        }])->get();
        $users = $role->users()->get();
        return view('roles.index', [
            'roles' => $roles,
            'entites' => $entites,
            'role' => $role,
            'users' => $users,
        ]);
    }

    // /**
    //  * Show the form for creating a new resource.
    //  */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'role_name' => 'required|string|max:255',
            'entite_id' => 'required|integer|exists:entites,id',
        ]);

        $existingRole = Role::where('name', $request->role_name)
            ->where('entite_id', $request->entite_id)
            ->first();

        if ($existingRole) {
            return redirect()->back()->withErrors(['role_name' => 'Un poste avec ce nom existe déjà.'])->withInput();
        }

        $role = new Role();
        $role->name = $request->role_name;
        $role->entite_id = $request->entite_id;
        $role->save();

        return redirect()->back()->with('status', 'Poste créé avec succès.');
    }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(Role $role)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(Role $role)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'role_name' => 'required|string|max:255',
            'entite_id' => 'required|integer|exists:entites,id',
        ]);

        $existingRole = Role::where('name', $request->role_name)
            ->where('entite_id', $request->entite_id)
            ->where('id', '!=', $role->id)
            ->first();

        if ($existingRole) {
            return redirect()->back()->withErrors(['role_name' => 'Un poste avec ce nom existe déjà.'])->withInput();
        }

        $role->name = $request->role_name;
        $role->entite_id = $request->entite_id;
        $role->save();

        return redirect()->back()->with('status', 'Poste mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        if ($role->undeletable) {
            return redirect()->back()->withErrors(['role' => 'Ce poste ne peut pas être désactivé.']);
        }

        $role->delete();
        if ($role->users()) {
            $userRole_id = Auth::user()->role->id;
            $role_cible = Role::withTrashed()->findOrFail($userRole_id);
            $messsage = 'Le poste <strong>' . $role->name . '</strong> a été désactivé par ' . Auth::user()->first_name .' '. Auth::user()->last_name . ', les utilisateurs affectés à ce poste ont été déconnectés :';
            foreach ($role->users as $user) {
                $messsage .= '<br/> - ' . $user->first_name . ' ' . $user->last_name;
            }
            Notification::createNotification(
                $role_cible,
                'system',
                'Poste désactivé',
                $messsage,
                'Voir le poste désactivé',
                "roles.index",
                ['role' => $role->id],
                'Voir le poste désactivé'
            );

        }
        return redirect()->back()->with('status', 'Poste désactivé avec succès.');
    }
    /**
     * Restore the specified resource from storage.
     */
    public function restore($id): RedirectResponse
    {
        $role = Role::withTrashed()->findOrFail($id);
        $role->restore();

        return redirect()->back()->with('status', 'Poste activé avec succès.');
    }
}
