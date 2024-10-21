<?php

namespace App\Http\Controllers;

use App\Models\Entite;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($role = 0): View
    {
        if ($role != 0) {
            $role = Role::findOrFail($role);
        } else {
            $role = Role::findOrFail(1);
        }

        return view('permissions.index', [
            'permissions' => Permission::all(),
            'roles' => Role::all(),
            'entites' => Entite::all(),
            'role' => $role,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $role_id = $request->role_id;
        $role = Role::findOrFail($role_id);
        // Detach all permissions first
        $role->permissions()->detach();

        // Attach the selected permissions
        foreach ($request->all() as $key => $value) {
            if ($key != '_token' && $key != 'role_id' && $key != '_method') {
                $role->permissions()->attach($value);
            }
        }

        return redirect()->route('permissions.index', ['role' => $role])->with('status', 'Permissions mises à jour');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        //
    }
}
