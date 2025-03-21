<?php

namespace App\Http\Controllers;

use App\Http\Resources\MatiereResource;
use App\Models\DossierStandard;
use App\Models\Famille;
use App\Models\Matiere;
use App\Models\MouvementStock;
use App\Models\Societe;
use App\Models\SousFamille;
use App\Models\Standard;
use App\Models\StandardVersion;
use App\Models\Stock;
use App\Models\Unite;
use Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;


class MatiereController extends Controller
{
    // Dans votre contrôleur

    /**
     * Méthode privée qui construit la requête de recherche principale.
     */
    private function buildMatiereQuery(Request $request)
    {
        $query = Matiere::with(['sousFamille', 'societe', 'standardVersion']);

        // Filtrer par famille
        if ($request->filled('famille')) {
            $query->whereHas('sousFamille', function ($subQuery) use ($request) {
                $subQuery->where('famille_id', $request->input('famille'));
            });
        }
        // Filtrer par sous-famille
        if ($request->filled('sous_famille')) {
            $query->where('sous_famille_id', $request->input('sous_famille'));
        }
        // Filtrer par societe (optionnel, utilisé uniquement dans quickSearch)
        // Filtrer par le champ "search"
        if ($request->filled('search')) {
            $search = $request->input('search');
            $terms = explode(' ', $search);
            $query->where(function ($q) use ($terms) {
                // Pour chaque terme, appliquer des filtres spécifiques
                foreach ($terms as $term) {
                    $q->where(function ($subQuery) use ($term, $terms) {
                        if (stripos($term, 'dn') === 0) {
                            $value = substr($term, 2);
                            $subQuery->where('dn', 'ILIKE', "%{$value}%");
                        } elseif (stripos($term, 'ep') === 0) {
                            $value = str_replace([',', '.'], ['.', ','], substr($term, 2));
                            $subQuery->where('epaisseur', 'ILIKE', "%{$value}%");
                        } else {
                            $subQuery->whereRaw("unaccent(designation) ILIKE unaccent(?)", ["%{$term}%"])
                                ->orWhereHas('sousFamille', function ($subSubQuery) use ($term) {
                                    $subSubQuery->where('nom', 'ILIKE', "%{$term}%");
                                })
                                ->orWhere('ref_interne', 'ILIKE', "%{$term}%");
                            if (count($terms) == 1) {
                                $subQuery->orWhereHas('societeMatieres', function ($subSubQuery) use ($term) {
                                    $subSubQuery->orWhere('ref_externe', 'ILIKE', "%{$term}%");
                                });
                            }
                        }
                    });
                }
            });
        }
        // dd($query->toSql(), $query->getBindings());
        return $query;
    }

    /**
     * Méthode searchResult avec pagination.
     */
    public function searchResult(Request $request, $wantJson = true)
    {
        // Validation des données d'entrée
        $request->validate([
            'search'       => 'nullable|string|max:255',
            'nombre'       => 'nullable|integer|min:1|max:10000',
            'famille'      => 'nullable|integer|exists:familles,id',
            'sous_famille' => 'nullable|integer|exists:sous_familles,id',
            'page'         => 'nullable|integer|min:1',
        ]);

        $nombre = intval($request->input('nombre', 50));

        // Construction de la requête avec la logique principale
        $query = $this->buildMatiereQuery($request);
        $query->orderBy('sous_famille_id');

        // Récupérer les résultats paginés
        $matieres = $query->paginate($nombre);

        if ($wantJson) {
            $links    = $matieres->appends($request->query())->links()->toHtml();
            $links    = str_replace('/search', '', $links);
            $lastPage = $matieres->lastPage();

            return response()->json([
                'matieres' => MatiereResource::collection($matieres),
                'links'    => $links,
                'lastPage' => $lastPage,
            ]);
        }

        return $matieres;
    }

    /**
     * Méthode quickSearch avec limite de 50 résultats.
     */
    public function quickSearch(Request $request)
    {
        // Validation des données d'entrée
        $request->validate([
            'search'         => 'nullable|string|max:255',
            'famille'        => 'nullable|integer|exists:familles,id',
            'sous_famille'   => 'nullable|integer|exists:sous_familles,id',
            'with_last_price' => 'nullable|boolean',
            'societe'        => 'nullable|integer|exists:societes,id',
        ]);

        // Construction de la requête avec la logique principale
        $query = $this->buildMatiereQuery($request);
        $query->orderBy('sous_famille_id')->limit(50);

        $matieres = $query->get();

        return response()->json([
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
        $sousFamilles = $famille->sousFamilles->map(function ($sousFamille) {
            $sousFamille->matiere_count = $sousFamille->matieres()->count();
            return $sousFamille;
        });

        return response()->json($sousFamilles);
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
        $fournisseurs = $matiere->fournisseurs()
            ->get();
        foreach ($fournisseurs as $fournisseur) {
            $fournisseur->prix = $matiere->getLastPrice($fournisseur->id);
            $fournisseur->ref_externe = $matiere->societeMatiere($fournisseur->id)->ref_externe;
        }

        $dates = $matiere->mouvementStocks ? null : $matiere->mouvementStocks->pluck('created_at');
        $quantites = $matiere->mouvementStocks->pluck('quantite');

        $quantitemouvement = 0;
        foreach ($matiere->mouvementStocks as $mouvement) {
            $quantitemouvement += $mouvement->quantite  * ($mouvement->type_mouvement ? 1 : -1);
        }
        $quantiteActuelle = $matiere->quantite() - $quantitemouvement;
        $quantites = $matiere->mouvementStocks->sortBy('created_at')->map(function ($mouvement) use (&$quantiteActuelle, $matiere) {

            $quantiteActuelle += $mouvement->quantite  * ($mouvement->type_mouvement ? 1 : -1);
            return $quantiteActuelle;
        });

        return view('matieres.show', [
            'matiere' => $matiere,
            'fournisseurs' => $fournisseurs,
            'dates' => $dates,
            'quantites' => $quantites,
        ]);
    }
    public function showPrix($matiere_id, $societe_id): View
    {
        $fournisseur = Societe::whereIn('societe_type_id', ['3', '2'])->findOrFail($societe_id);
        $matiere = Matiere::with(['sousFamille', 'societe', 'standardVersion'])->findOrFail($matiere_id);
        $fournisseurs_prix = $matiere->prixPourSociete($societe_id)
            ->orderBy('date', 'desc')
            ->get();
        $dates = $fournisseurs_prix->pluck('date');
        $prix = $fournisseurs_prix->pluck('prix_unitaire');
        return view('matieres.show_prix', [
            'matiere' => $matiere,
            'fournisseur' => $fournisseur,
            'fournisseurs_prix' => $fournisseurs_prix,
            'dates' => $dates,
            'prix' => $prix,
        ]);
    }
    public function mouvement(Request $request, $matiereId)
    {
        $request->validate([
            'type' => 'required|in:entree,sortie',
            'quantite' => 'required|integer|min:1',
            'valeur_unitaire' => 'nullable|numeric|min:0',
            'raison' => 'nullable|string|max:255',
        ]);

        $matiere = Matiere::findOrFail($matiereId);
        $valeurUnitaire = $request->valeur_unitaire ?? $matiere->ref_valeur_unitaire;

        if (!$valeurUnitaire) {
            return response()->json(['error' => 'Aucune valeur unitaire définie.'], 400);
        }

        $stock = Stock::firstOrCreate(
            ['matiere_id' => $matiere->id, 'valeur_unitaire' => $valeurUnitaire]
        );

        if ($request->type == 'entree') {
            $stock->quantite += $request->quantite;
        } elseif ($request->type == 'sortie') {
            if ($stock->quantite < $request->quantite) {
                return response()->json(['error' => 'Stock insuffisant.'], 400);
            }
            $stock->quantite -= $request->quantite;
        }

        $stock->save();

        MouvementStock::create([
            'matiere_id' => $matiere->id,
            'user_id' => Auth::id(),
            'type' => $request->type,
            'quantite' => $request->quantite,
            'valeur_unitaire' => $valeurUnitaire,
            'raison' => $request->raison,
            'date' => now(),
        ]);

        return response()->json([
            'message' => 'Mouvement enregistré avec succès.',
            'stock' => $stock
        ]);
    }



    public function retirerMouvement($matiere_id, Request $request)
    {
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
    public function quickCreate($modal_id): View
    {

        $familles = Famille::all();
        $dossier_standards = DossierStandard::all();
        $unites = Unite::all();
        return view('matieres.quick_create', [
            'familles' => $familles,
            'unites' => $unites,
            'modal_id' => $modal_id,
            'dossier_standards' => $dossier_standards,
        ]);
    }
    public function quickStore(Request $request)
    {
        $request->validate([
            'standard_id' => 'nullable|exists:standards,nom',
            'standard_version_id' => 'nullable|exists:standard_versions,version',
            'designation' => 'required|string|max:255',
            'unite_id' => 'required|exists:unites,id',
            'sous_famille_id' => 'required|exists:sous_familles,id',
            'dn' => 'nullable|string|max:50',
            'epaisseur' => 'nullable|string|max:50',
            'quantite' => 'required|integer',
            'stock_min' => 'required|integer',
        ]);
        $lastref = Matiere::max('id') + 1;
        $dn = $request->input('dn') ?: null;
        $epaisseur = $request->input('epaisseur') ?: null;
        if ($request->input('standard_version_id') === '' || $request->input('standard_id') === '') {
            $standard_version = null;
        } else {
            $standard_id = Standard::where('nom', 'ILIKE', $request->input('standard_id'))->first()->id;
            if ($standard_id === null) {
                return response()->json(['error' => 'Le standard n\'existe pas'], 422);
            }
            $standard_version_id = StandardVersion::where('version', 'ILIKE', $request->input('standard_version_id'))
                ->where('standard_id', $standard_id)
                ->first()->id;
            if ($standard_version_id === null) {
                return response()->json(['error' => 'La version du standard n\'existe pas'], 422);
            }
        }
        $matiere = Matiere::create(
            [
                'ref_interne' => 'AA-' . str_pad($lastref, 5, '0', STR_PAD_LEFT),
                'designation' => $request->input('designation'),
                'unite_id' => $request->input('unite_id'),
                'sous_famille_id' => $request->input('sous_famille_id'),
                'standard_version_id' => $standard_version_id,
                'dn' => $dn,
                'epaisseur' => $epaisseur,
                'prix_moyen' => null,
                'date_dernier_achat' => null,
                'quantite' => $request->input('quantite'),
                'stock_min' => $request->input('stock_min'),
            ]
        );

        return response()->json([
            'success' => true,
            'matiere' => $matiere,
        ], 201);
    }
    public function storeSousFamille(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255|unique:sous_familles,nom',
            'famille_id' => 'required|exists:familles,id',
        ]);

        $sousFamille = SousFamille::create($request->all());

        return response()->json([
            'success' => true,
            'sousFamille' => $sousFamille,
        ], 201);
    }
}
