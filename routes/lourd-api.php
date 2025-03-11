<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

Route::get('/lourd-api/login', function (Request $request) {
    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['success' => false, 'message' => 'Identifiants invalides'], 401);
    } elseif ($user->role_id != 1) {
        return response()->json(['success' => false, 'message' => 'Accès refusé'], 403);
    }
    return response()->json(['success' => true, 'message' => 'Authentification réussie']);
});
