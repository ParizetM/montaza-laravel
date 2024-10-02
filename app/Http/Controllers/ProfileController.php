<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\Role;

class ProfileController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $show_deleted = $request->input('show_deleted');
        if ($show_deleted) {
            $users = User::onlyTrashed()->get();
            return view(
                'profile.index',
                [
                    'users' => $users
                ]
            );
        } else {
            // Rechercher des utilisateurs en fonction du terme de recherche (si fourni)
            $users = User::query()
                ->when($search, function ($query, $search) {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhereHas('role', function ($query) use ($search) {
                            $query->where('name', 'like', "%{$search}%");
                        });
                })
                ->with('role') // Ensure the role relationship is loaded
                ->get();

            return view(
                'profile.index',
                [
                    'users' => $users
                ]
            );
        }
    }
    /**
     * Display the user's profile form.
     */
    public function edit(int $id): View
    {

        $user = User::findOrFail($id);
        if (Auth::user()->role_id != 1 && $user->id != Auth::id()) {
            abort(404);
        }
        $roles = Role::all();
        return view('profile.edit', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = User::findOrFail($request->id);
        $user->update($request->only(['first_name', 'last_name', 'phone', 'email']));

        return Redirect::route('profile.edit', ['id' => $user->id])->with('status', 'profil de modifié');
    }
    public function updateAdmin(Request $request): RedirectResponse
    {
        /** @var \App\Models\User $user */
        $user = User::findOrFail($request->id);
        $user->update($request->only(['role_id']));

        return Redirect::route('profile.edit', ['id' => $user->id])->with('status', 'profil modifié');
    }

    /**
     * Summary of destroy
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        $user = User::findOrFail($user->id);
        $user->delete();
        return Redirect::route('profile.index')->with('status', "compte $user->first_name $user->last_name supprimé");
    }
    /**
     * Summary of restore
     * @param int $int
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore(int $int): RedirectResponse
    {
        $user = User::withTrashed()->findOrFail($int);
        if ($user->trashed()) {
            $user->restore();
            return Redirect::route('profile.index')->with('status', "Compte $user->first_name $user->last_name restauré");
        } else {
            return Redirect::route('profile.index')->with('status', "Compte $user->first_name $user->last_name n'est pas supprimé");
        }
    }
}
