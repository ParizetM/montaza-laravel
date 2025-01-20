<?php

namespace App\Http\Controllers;

use App\Http\Resources\MatiereResource;
use App\Models\Famille;
use App\Models\Matiere;
use App\Models\Societe;
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
            'famille' => 'nullable|integer|exists:familles,id',
            'sous_famille' => 'nullable|integer|exists:sous_familles,id',
            'page' => 'nullable|integer|min:1',
        ]);

        $search = $request->input('search', '');
        $nombre = intval($request->input('nombre', 50));
        $famille = $request->input('famille', '');
        $sousFamille = $request->input('sous_famille', '');
        // Génération d'une clé de cache unique
        $cacheKey = sprintf(
            'matieres_search_%s_%s_%s_%s_%s',
            md5($search),
            $famille ?: 'all',
            $sousFamille ?: 'all',
            $nombre,
            $request->input('page', 1)
        );

        // Récupération ou mise en cache des résultats
        $matieres = Cache::remember($cacheKey, now()->addMinutes(10), function () use ($search, $nombre, $sousFamille, $famille) {
            $query = Matiere::with(['sousFamille', 'societe', 'standardVersion']);

            if (!empty($famille)) {
                $query->whereHas('sousFamille', function ($subQuery) use ($famille) {
                    $subQuery->where('famille_id', $famille);
                });
            }
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
            $links = $matieres->appends(request()->query())->links()->toHtml();
            $lastpage = $matieres->lastPage();
            $links = str_replace('/search', '', $links);
            return response()->json(data: [
                'matieres' => MatiereResource::collection($matieres),
                'links' => $links,
                'lastPage' => $lastpage,
            ]);
        }

        return $matieres;
    }
    public function quickSearch(Request $request)
    {
        // Validation des données d'entrée
        $request->validate([
            'search' => 'nullable|string|max:255',
            'famille' => 'nullable|integer|exists:familles,id',
            'sous_famille' => 'nullable|integer|exists:sous_familles,id',
        ]);

        $search = $request->input('search', '');
        $famille = $request->input('famille', '');
        $sousFamille = $request->input('sous_famille', '');
        // Génération d'une clé de cache unique
        // Récupération ou mise en cache des résultats
        $query = Matiere::with(['sousFamille', 'societe', 'standardVersion']);

        if (!empty($famille)) {
            $query->whereHas('sousFamille', function ($subQuery) use ($famille) {
                $subQuery->where('famille_id', $famille);
            });
        }
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

        $query->orderBy('sous_famille_id')->limit(15)->get();
        $matieres = $query->get();
        return response()->json(data: [
            'matieres' => MatiereResource::collection($matieres),
        ]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {

        $familles = Famille::all();
        return view('matieres.index', [
            'familles' => $familles,
        ]);
    }


    public function sousFamillesJson(Famille $famille)
    {
        return response()->json($famille->sousFamilles);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ref_interne' => 'required|string|unique:matieres,ref_interne',
            'standard_id' => 'nullable|exists:standards,id',
            'designation' => 'required|string|max:255',
            'societe_id' => 'required|exists:societes,id',
            'unite_id' => 'required|exists:unites,id',
            'sous_famille_id' => 'required|exists:sous_familles,id',
            'dn' => 'nullable|integer',
            'epaisseur' => 'nullable|numeric',
            'quantite' => 'required|integer',
            'stock_min' => 'nullable|integer',
        ]);

        $matiere = Matiere::create($request->all());

        return response()->json(new MatiereResource($matiere), 201);
    }
    public function fournisseursJson(Matiere $matiere)
    {
        return response()->json($matiere->fournisseurs);
    }
    public function show($matiere_id): View
    {
        $matiere = Matiere::with(['sousFamille', 'societe', 'standardVersion'])->findOrFail($matiere_id);
        $fournisseurs_dernier_prix = $matiere->fournisseurs()
            ->orderBy('date_dernier_prix', 'desc')
            ->get()
            ->unique('pivot.societe_id');
        return view('matieres.show', [
            'matiere' => $matiere,
            'fournisseurs_dernier_prix' => $fournisseurs_dernier_prix,
        ]);
    }
    public function showPrix($matiere_id, $societe_id): View
    {
        $fournisseur = Societe::findOrFail($societe_id)->where('societe_type_id', ['3', '2'])->first();
        $matiere = Matiere::with(['sousFamille', 'societe', 'standardVersion'])->findOrFail($matiere_id);
        $fournisseurs_prix = $matiere->fournisseurs()
            ->where('societe_id', $societe_id)
            ->orderBy('date_dernier_prix', 'desc')
            ->get();
        $dates = $fournisseurs_prix->pluck('pivot.date_dernier_prix');
        $prix = $fournisseurs_prix->pluck('pivot.prix');
        return view('matieres.show_prix', [
            'matiere' => $matiere,
            'fournisseur' => $fournisseur,
            'fournisseurs_prix' => $fournisseurs_prix,
            'dates' => $dates,
            'prix' => $prix,
        ]);
    }
}
