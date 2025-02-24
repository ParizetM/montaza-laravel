<?php

namespace App\Http\Controllers;

use App\Http\Resources\MatiereResource;
use App\Http\Resources\MatiereResourceWithPrice;
use App\MatiereMouvement;
use App\Models\Famille;
use App\Models\Matiere;
use App\Models\Societe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;
use Ramsey\Uuid\Type\Decimal;

class MatiereController extends Controller
{
    public function searchResult(Request $request, $wantJson = true)
    {
        // Validation des données d'entrée
        $request->validate([
            'search' => 'nullable|string|max:255',
            'nombre' => 'nullable|integer|min:1|max:10000',
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
                    $q->where('designation', 'ILIKE', "%{$search}%")
                        ->orWhereHas('sousFamille', function ($subQuery) use ($search) {
                            $subQuery->where('nom', 'ILIKE', "%{$search}%");
                        })
                        ->orWhereHas('societe', function ($subQuery) use ($search) {
                            $subQuery->where('raison_sociale', 'ILIKE', "%{$search}%");
                        })
                        ->orWhere('ref_interne', 'ILIKE', "%{$search}%")
                        ->orWhere('quantite', 'ILIKE', "%{$search}%");
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
            'with_last_price' => 'nullable|boolean',
            'societe' => 'nullable|integer|exists:societes,id',
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
                $q->where('designation', 'ILIKE', "%{$search}%")
                    ->orWhereHas('sousFamille', function ($subQuery) use ($search) {
                        $subQuery->where('nom', 'ILIKE', "%{$search}%");
                    })
                    ->orWhereHas('societe', function ($subQuery) use ($search) {
                        $subQuery->where('raison_sociale', 'ILIKE', "%{$search}%");
                    })
                    ->orWhere('ref_interne', 'ILIKE', "%{$search}%");
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
            ->where('societe_type_id', ['3', '2'])
            ->orderBy('date_dernier_prix', 'desc')
            ->get()
            ->unique('pivot.societe_id');
        $dates = $matiere->mouvements->isEmpty() ? null : $matiere->mouvements->pluck('created_at');
        $quantites = $matiere->mouvements->pluck('pivot.quantite');

        $quantitemouvement = 0;
        foreach ($matiere->mouvements as $mouvement) {
            $quantitemouvement += $mouvement->quantite  * ($mouvement->type_mouvement ? 1 : -1);
        }
        $quantiteActuelle = $matiere->quantite - $quantitemouvement;
        $quantites = $matiere->mouvements->sortBy('created_at')->map(function ($mouvement) use (&$quantiteActuelle,$matiere) {

            $quantiteActuelle += $mouvement->quantite  * ($mouvement->type_mouvement ? 1 : -1);
            return $quantiteActuelle;
        });

        return view('matieres.show', [
            'matiere' => $matiere,
            'fournisseurs_dernier_prix' => $fournisseurs_dernier_prix,
            'dates' => $dates,
            'quantites' => $quantites,
        ]);
    }
    public function showPrix($matiere_id, $societe_id): View
    {
        $fournisseur = Societe::where('societe_type_id', ['3', '2'])->findOrFail($societe_id);
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

    public function mouvement($matiere_id, float $quantite, bool $type) {
        $matiere = Matiere::findOrFail($matiere_id);
        if ($matiere->quantite + $quantite * ($type ? 1 : -1) < 0) {
            return false;
        }
        $mouvement = new MatiereMouvement([
            'quantite' => $quantite,
            'type_mouvement' => $type,
        ]);
        $matiere->mouvements()->save($mouvement);
        $matiere->quantite += $quantite * ($type ? 1 : -1);
        $matiere->save();
        return true;
    }
    public function retirerMouvement($matiere_id,Request $request) {
        $matiere = Matiere::findOrFail($matiere_id);
        $request->validate([
            'quantite' => 'required|numeric',
            'type' => 'required|boolean',
        ]);
        $test = $this->mouvement($matiere_id, $request->input('quantite'), type: $request->input('type'));
        if (!$test) {
            return back()->with('error', "Impossible de retirer {$request->input('quantite')} {$matiere->unite->short} à {$matiere->designation}");
        }
        return back()->with('success', "{$request->input('quantite')} {$matiere->unite->short} {$matiere->designation} a été retiré avec succès");
    }
}
