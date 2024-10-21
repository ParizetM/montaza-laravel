<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entite;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($role = 0)
    {
        if ($role !== 0) {
            $role = Role::findOrFail($role);
        } else {
            $role = Role::findOrFail(1);
        }
        $roles = Role::all();
        $entites = Entite::all();
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
            return redirect()->back()->withErrors(['role_name' => 'Un rôle avec ce nom existe déjà.'])->withInput();
        }

        $role = new Role();
        $role->name = $request->role_name;
        $role->entite_id = $request->entite_id;
        $role->save();

        return redirect()->back()->with('status', 'Rôle créé avec succès.');
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

    }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(Role $role)
    // {
    //     //
    // }
}
