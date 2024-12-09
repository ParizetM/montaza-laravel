<?php

namespace App\Http\Controllers;

use App\Models\Societe;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use App\Models\SocieteType;
use App\Models\Commentaire;

class SocieteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search', ''); // Valeur par défaut si non défini
        $type = $request->input('type', ''); // Valeur par défaut si non défini
        $nombre = intval($request->input('nombre', 20)); // Conversion sécurisée en entier

        // Définir une clé de cache unique pour cette requête
        $cacheKey = 'societes_' . md5($search . $type . $nombre . $request->input('page', 1)); // Inclure la page et le type dans la clé de cache

        // Vérifier si les résultats sont en cache
        $societes = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($search, $nombre, $type) {
            $query = Societe::with(['societeType', 'formeJuridique', 'codeApe', 'etablissements.societeContacts']);
            if (!empty($type)) {
                $query->where('societe_type_id', '=', $type);
            }
            // Si un terme de recherche est fourni
            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('raison_sociale', 'like', "%{$search}%")
                        ->orWhereHas('formeJuridique', function ($subQuery) use ($search) {
                            $subQuery->where('nom', 'like', "%{$search}%");
                        })
                        ->orWhereHas('codeApe', function ($subQuery) use ($search) {
                            $subQuery->where('code', 'like', "%{$search}%");
                        });
                });
            }

            // Ajout d'un tri et d'une pagination
            return $query->orderBy('societe_type_id')->paginate($nombre);
        });
        return view('societes.index', [
            'societes' => $societes,
            'societeTypes' => SocieteType::all()->reverse(),
        ]);
    }
    public function updateCommentaire(Request $request, $id)
    {
        $societe = Societe::find($id);
        if ($societe) {
            // Trouve le commentaire lié à la société
            $commentaire = $societe->commentaire;
            if ($commentaire) {
                if ($commentaire->contenu == $request->commentaire) {
                    return response()->json(['message' => 'Commentaire inchangé'], 200);
                }
                // Met à jour le commentaire avec la nouvelle valeur
                $commentaire->contenu = $request->commentaire;
                $commentaire->save();
            } else {
                // Si la société n'a pas encore de commentaire, on en crée un
                $commentaire = new Commentaire();
                $commentaire->contenu = $request->commentaire;
                $societe->commentaire()->save($commentaire);
            }
        }

        return response()->json(['message' => 'Commentaire mis à jour'], 200);
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
