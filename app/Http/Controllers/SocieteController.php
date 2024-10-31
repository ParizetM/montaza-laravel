<?php

namespace App\Http\Controllers;

use App\Models\Societe;
use Illuminate\Http\Request;

class SocieteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

    $search = $request->input('search');
    $nombre = $request->input('nombre') ?? 50;
    if (!is_int($nombre)) {
        $nombre = 50;
    }

    if ($search) {
        $societes = Societe::query();

        if ($search) {
            $societes->where('raison_sociale', 'like', '%' . $search . '%')
                ->orWhereHas('formeJuridique', function ($query) use ($search) {
                    $query->where('nom', 'like', '%' . $search . '%');
                })
                ->orWhereHas('codeApe', function ($query) use ($search) {
                    $query->where('code', 'like', '%' . $search . '%');
                })
                ->orWhereHas('societeType', function ($query) use ($search) {
                    $query->where('nom', 'like', '%' . $search . '%');
                });
        }
        $societes = $societes->orderBy('societe_type_id')
            ->take(50)
            ->get();
    } else {
        $societes = Societe::orderBy('societe_type_id')
            ->take($nombre)
            ->get();
    }
        return view('societes.index', compact('societes'));
    }

    // /**
    //  * Show the form for creating a new resource.
    //  */
    // public function create()
    // {
    //     //
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request)
    // {
    //     //
    // }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(Societe $societe)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(Societe $societe)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, Societe $societe)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(Societe $societe)
    // {
    //     //
    // }
}
