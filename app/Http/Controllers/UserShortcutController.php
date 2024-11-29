<?php

namespace App\Http\Controllers;

use App\Models\PredefinedShortcut;
use App\Models\UserShortcut;
use Auth;
use Illuminate\Http\Request;

class UserShortcutController extends Controller
{
    public function index()
    {
        $shortcuts = PredefinedShortcut::all();
        $userShortcuts = Auth::user()->shortcuts();

        return view('shortcuts.index', [
            'shortcuts' => $shortcuts,
            'userShortcuts' => $userShortcuts,
        ]);
    }

    public function store(Request $request)
    {
        // Remove all existing shortcuts for the user
        UserShortcut::where('user_id', Auth::id())->delete();

        // Add new shortcuts
        foreach ($request->keys() as $key) {
            if (preg_match('/^is_added-([0-9]+)$/', $key)) {
            $id = explode('-', $key)[1];
            echo $id;
            UserShortcut::create([
                'user_id' => Auth::id(),
                'shortcut_id' => $id,
            ]);

            }
        }
        return back()->with('message', 'Raccourcis mis à jour');
    }

    public function destroy($id)
    {
        $userShortcut = UserShortcut::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $userShortcut->delete();

        return response()->json(['message' => 'Raccourci supprimé']);
    }
}
