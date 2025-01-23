<?php

namespace App\Http\Controllers;

use App\Models\Entite;
use Illuminate\Http\Request;

class AdministrationController extends Controller
{
    public function index()
    {
        return view('administration.index');
    }
    public function info($id = 1)
    {
        $entites = Entite::all();
        $entite = $id ? Entite::find($id) : null;
        return view('administration.info', compact('entites', 'entite'));
    }
    public function update(Request $request, $id)
    {
        $entite = Entite::find($id);
        $request->validate([
            'name' => 'required|string|max:255',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'code_postal' => 'required|string|max:255',
            'tel' => 'required|string|max:255',
            'siret' => 'required|string|max:255',
            'rcs' => 'required|string|max:255',
            'numero_tva' => 'required|string|max:255',
            'code_ape' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
            $request->merge(['logo' => $logoPath]);
        }

        $entite->update($request->all());
        return redirect()->route('administration.info', $id);
    }
}
