<?php

namespace App\Http\Controllers;

use App\Models\Societe;
use App\Models\SocieteContact;
use Cache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use \Illuminate\Contracts\View\View;

class SocieteContactController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'societe_id' => 'nullable',
            'etablissement_id' => 'required|integer|exists:etablissements,id',
            'nom' => 'required|string',
            'email' => 'required|email',
            'telephone_portable' => 'nullable|string',
            'telephone_fixe' => 'nullable|string',
            'fonction' => 'nullable|string',
        ]);
        Cache::flush();
        SocieteContact::create($request->all());

        return response()->json(['success' => true]);
    }

    public function quickCreate(): View
    {
        $societes = Societe::select('id', 'raison_sociale')->get();
        return view('societes.contacts.quick-create', compact('societes'));
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
