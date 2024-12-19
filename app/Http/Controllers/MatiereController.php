<?php

namespace App\Http\Controllers;

use App\Http\Resources\MatiereResource;
use App\Models\Famille;
use App\Models\Matiere;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class MatiereController extends Controller
{
    public function searchResult(Request $request, $wantJson = true)
    {
        // Validation des données d'entrée
        $request->validate([
            'search' => 'nullable|string|max:255',
            'nombre' => 'nullable|integer|min:1|max:100',
            'sous_famille' => 'nullable|integer|exists:sous_familles,id',
            'page' => 'nullable|integer|min:1',
        ]);

        $search = $request->input('search', '');
        $nombre = intval($request->input('nombre', 50));
        $sousFamille = $request->input('sous_famille', '');

        // Génération d'une clé de cache unique
        $cacheKey = sprintf(
            'matieres_search_%s_%s_%s_page_%d',
            md5($search),
            $sousFamille ?: 'all',
            $nombre,
            $request->input('page', 1)
        );

        // Récupération ou mise en cache des résultats
        $matieres = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($search, $nombre, $sousFamille) {
            $query = Matiere::with(['sousFamille', 'societe', 'standard']);

            if (!empty($sousFamille)) {
                $query->where('sous_famille_id', $sousFamille);
            }

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('designation', 'like', "%{$search}%")
                        ->orWhereHas('sousFamille', function ($subQuery) use ($search) {
                            $subQuery->where('nom', 'like', "%{$search}%");
                        })
                        ->orWhereHas('societe', function ($subQuery) use ($search) {
                            $subQuery->where('raison_sociale', 'like', "%{$search}%");
                        })
                        ->orWhere('ref_interne', 'like', "%{$search}%");
                });
            }

            return $query->orderBy('sous_famille_id')->paginate($nombre);
        });

        // Retourner les résultats
        if ($wantJson) {
            $pagination = $matieres->appends($request->all())->toArray();
            $pagination['links'] = $matieres->links()->toHtml();
            return response()->json(data: [
                'matieres' => MatiereResource::collection($matieres),
                'links' => $matieres->links()->toHtml(),
        ]);
        }

        return $matieres;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {

        $familles = Famille::all();
        $matieres = $this->searchResult($request, false);
        return view('matieres.index', [
            'familles' => $familles,
            'matieres' => $matieres,
        ]);
    }


    public function sousFamillesJson(Famille $famille)
    {
        return response()->json($famille->sousFamilles);
    }
}
