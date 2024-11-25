<?php

namespace App\Http\Controllers;

use App\Models\Societe;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;

class SocieteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search', ''); // Valeur par défaut si non défini
        $nombre = intval($request->input('nombre', 20)); // Conversion sécurisée en entier

        // Définir une clé de cache unique pour cette requête
        $cacheKey = 'societes_' . md5($search . $nombre); // Utilise une combinaison unique de la recherche et de la quantité

        // Vérifier si les résultats sont en cache
        $societes = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($search, $nombre) {
            $query = Societe::with(['societeType', 'formeJuridique', 'codeApe', 'etablissements.societeContacts']);

            // Si un terme de recherche est fourni
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('raison_sociale', 'like', '%' . $search . '%')
                        ->orWhereHas('formeJuridique', function ($subQuery) use ($search) {
                            $subQuery->where('nom', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('codeApe', function ($subQuery) use ($search) {
                            $subQuery->where('code', 'like', '%' . $search . '%');
                        })
                        ->orWhereHas('societeType', function ($subQuery) use ($search) {
                            $subQuery->where('nom', 'like', '%' . $search . '%');
                        });
                });
            }

            // Ajout d'un tri et d'une pagination
            return $query->orderBy('societe_type_id')->paginate($nombre);
        });

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
