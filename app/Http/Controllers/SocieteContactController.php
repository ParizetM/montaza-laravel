<?php

namespace App\Http\Controllers;

use App\Models\SocieteContact;
use Cache;
use Illuminate\Http\Request;

class SocieteContactController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'etablissement_id' => 'required|integer|exists:etablissements,id',
            'nom' => 'required|string',
            'email' => 'required|email',
            'telephone_portable' => 'nullable|string',
            'telephone_fixe' => 'nullable|string',
            'fonction' => 'nullable|string',
        ]);
        Cache::flush();
        $societeContact = SocieteContact::create($request->all());

        return redirect()->back()->with('success', 'Le contact a bien été ajouté.');
    }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(SocieteContact $societeContact)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(SocieteContact $societeContact)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, SocieteContact $societeContact)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(SocieteContact $societeContact)
    // {
    //     //
    // }
}
