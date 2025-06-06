<?php

namespace App\Http\Controllers;

use App\Http\Resources\MatiereResource;
use App\Models\DossierStandard;
use App\Models\Famille;
use App\Models\Material;
use App\Models\Matiere;
use App\Models\MouvementStock;
use App\Models\Societe;
use App\Models\SocieteMatiere;
use App\Models\SousFamille;
use App\Models\Standard;
use App\Models\StandardVersion;
use App\Models\Stock;
use App\Models\Unite;
use App\Services\StockService;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Log;

class MatiereController extends Controller
{
    // Dans votre contrôleur

    /**
     * Méthode privée qui construit la requête de recherche principale.
     */
    private function buildMatiereQuery(Request $request, $second_search = false)
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

        if ($request->filled('search')) {
            $search = $request->input('search');
            $terms = explode(' ', $search);

            if (count($terms) == 1 && !$second_search) {
                // Première tentative : recherche exacte sur ref_interne et ref_externe
                $query->where(function ($q) use ($search) {
                    $q->where('ref_interne', '=', $search)
                        ->orWhereHas('societeMatieres', function ($subSubQuery) use ($search) {
                            $subSubQuery->where('ref_externe', 'ILIKE', $search);
                        });
                });

                $query_test = clone $query;
                $query_test = $query_test->get();

                if ($query_test->isEmpty()) {
                    Log::info("No results found for single term search: {$search}");
                    // Deuxième tentative : recherche flexible
                    return $this->buildMatiereQuery($request, true);
                }
            } else {
                // Recherche multi-termes OU deuxième tentative pour terme unique
                $query->where(function ($q) use ($terms, $second_search) {
                    $hasValidSearchTerms = false;

                    foreach ($terms as $term) {
                        $q->where(function ($subQuery) use ($term, $terms, &$hasValidSearchTerms, $second_search) {
                            if (stripos($term, 'dn') === 0) {
                                $value = substr($term, 2);
                                $subQuery->where('dn', '=', $value);
                                $hasValidSearchTerms = true;
                            } elseif (stripos($term, 'ep') === 0) {
                                $value = str_replace([',', '.'], ['.', ','], substr($term, 2));
                                $subQuery->where('epaisseur', '=', $value);
                                $hasValidSearchTerms = true;
                            } else {
                                // Seulement si le terme fait au moins 2 caractères
                                if (strlen(trim($term)) >= 2) {
                                    $subQuery->whereRaw("unaccent(designation) ILIKE unaccent(?)", ["%{$term}%"])
                                        ->orWhereHas('sousFamille', function ($subSubQuery) use ($term) {
                                            $subSubQuery->where('nom', 'ILIKE', "%{$term}%");
                                        })
                                        ->orWhere('ref_interne', 'ILIKE', "%{$term}%");

                                    // Pour une recherche avec un seul terme (première ou deuxième tentative)
                                    if (count($terms) == 1) {
                                        $subQuery->orWhereHas('societeMatieres', function ($subSubQuery) use ($term) {
                                            $subSubQuery->where('ref_externe', 'ILIKE', "%{$term}%");
                                        });
                                    }
                                    $hasValidSearchTerms = true;
                                }
                            }
                        });
                    }

                    // Si aucun terme valide n'a été trouvé, forcer une condition impossible
                    if (!$hasValidSearchTerms) {
                        $q->whereRaw('1 = 0');
                    }
                });

                // Ajouter un score de pertinence pour ordonner les résultats
                $relevanceScore = [];
                foreach ($terms as $index => $term) {
                    if (strlen(trim($term)) >= 2 && stripos($term, 'dn') !== 0 && stripos($term, 'ep') !== 0) {
                        // Score pour sous-famille (priorité 3)
                        $relevanceScore[] = "CASE WHEN EXISTS (
                            SELECT 1 FROM sous_familles sf
                            WHERE sf.id = matieres.sous_famille_id
                            AND unaccent(sf.nom) ILIKE unaccent('%{$term}%')
                        ) THEN 3 ELSE 0 END";

                        // Score pour ref_interne (priorité 2)
                        $relevanceScore[] = "CASE WHEN unaccent(matieres.ref_interne) ILIKE unaccent('%{$term}%') THEN 2 ELSE 0 END";

                        // Score pour désignation (priorité 1)
                        $relevanceScore[] = "CASE WHEN unaccent(matieres.designation) ILIKE unaccent('%{$term}%') THEN 1 ELSE 0 END";
                    }
                }

                if (!empty($relevanceScore)) {
                    $scoreExpression = implode(' + ', $relevanceScore);
                    $query->selectRaw("*, ($scoreExpression) as relevance_score");
                    $query->orderBy('relevance_score', 'desc');
                }
            }
        }

        // Add sorting by stock quantity if requested (après le tri par pertinence)
        $query->addSelect(['total_stock' => function ($q) {
            $q->selectRaw('COALESCE(SUM(CASE WHEN stocks.valeur_unitaire > 0 THEN stocks.quantite * stocks.valeur_unitaire ELSE stocks.quantite END), 0)')
                ->from('stocks')
                ->whereColumn('stocks.matiere_id', 'matieres.id');
        }]);

        // Si pas de tri par pertinence, trier par stock
        if (!$request->filled('search') || (count(explode(' ', $request->input('search'))) == 1 && !$second_search)) {
            $query->orderBy('total_stock', 'desc');
        } else {
            // Trier d'abord par pertinence, puis par stock en cas d'égalité
            $query->orderBy('total_stock', 'desc');
        }

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

        // Get historical movement data for charts
        $mouvements = $matiere->mouvementStocks()->orderBy('date', 'asc')->get();
        $dates = $mouvements->pluck('date');

        // Initialize tracking variables
        $stockHistory = [];
        $currentStock = [];

        // For type 2 materials (tracked by unit value)
        if ($matiere->typeAffichageStock() == 2) {
            // Group stocks by valeur_unitaire
            $stocksByValue = $matiere->stock->groupBy('valeur_unitaire');

            // Initialize current stock for each value
            foreach ($stocksByValue as $valeur => $stocks) {
                $currentStock[$valeur] = 0;
            }

            // Calculate running total for each movement
            foreach ($mouvements as $mouvement) {
                $valeur = $mouvement->valeur_unitaire;

                // Initialize if this unit value wasn't seen before
                if (!isset($currentStock[$valeur])) {
                    $currentStock[$valeur] = 0;
                }

                // Update the stock based on movement type
                if ($mouvement->type == 'entree') {
                    $currentStock[$valeur] += $mouvement->quantite;
                } else {
                    $currentStock[$valeur] -= $mouvement->quantite;
                }

                // Store the point in time snapshot
                $stockHistory[] = [
                    'date' => $mouvement->date,
                    'valeur_unitaire' => $valeur,
                    'quantite' => $currentStock[$valeur],
                    'total' => array_sum($currentStock)
                ];
            }

            // Get total quantity for chart
            $quantites = collect($stockHistory)->pluck('total');
        }
        // For type 1 materials (simple quantity tracking)
        else {
            $currentQuantity = 0;

            // Calculate running total for each movement
            foreach ($mouvements as $mouvement) {
                if ($mouvement->type == 'entree') {
                    $currentQuantity += $mouvement->quantite;
                } else {
                    $currentQuantity -= $mouvement->quantite;
                }

                $stockHistory[] = [
                    'date' => $mouvement->date,
                    'quantite' => $currentQuantity
                ];
            }

            // Get total quantity for chart
            $quantites = collect($stockHistory)->pluck('quantite');
        }

        // Convert to collections for the view
        $stockHistory = collect($stockHistory);
        $mouvements = $matiere->mouvementStocks->sortByDesc('created_at');
        $societes = Societe::fournisseurs()->get();
        return view('matieres.show', [
            'matiere' => $matiere,
            'fournisseurs' => $fournisseurs,
            'dates' => $dates,
            'mouvements' => $mouvements,
            'quantites' => $quantites,
            'societes' => $societes,
        ]);
    }

    /**
     * Méthode privée pour construire la requête de prix pour une société donnée
     */
    private function buildPrixQuery(Request $request, $matiere_id, $societe_id)
    {
        $query = \App\Models\SocieteMatierePrix::with(['societeMatiere', 'societeMatiere.matiere'])
            ->whereHas('societeMatiere', function ($q) use ($matiere_id, $societe_id) {
                $q->where('matiere_id', $matiere_id)
                    ->where('societe_id', $societe_id);
            });

        // Filtre par date
        if ($request->filled('date_debut')) {
            $query->where('date', '>=', $request->input('date_debut'));
        }
        if ($request->filled('date_fin')) {
            $query->where('date', '<=', $request->input('date_fin'));
        }

        return $query;
    }

    /**
     * Affiche l'historique des prix pour une matière et un fournisseur
     */
    public function historiquePrix($matiere_id, $societe_id, Request $request): View
    {
        $matiere = Matiere::findOrFail($matiere_id);
        $societe = Societe::whereIn('societe_type_id', ['2', '3'])->findOrFail($societe_id);

        // Construction de la requête avec les filtres
        $query = $this->buildPrixQuery($request, $matiere_id, $societe_id);

        // Récupérer les prix avec pagination
        $prixList = $query->orderBy('date', 'desc')->paginate(20);

        // Conserver les paramètres de filtrage dans la pagination
        $prixList->appends($request->query());

        return view('matieres.historique_prix', [
            'matiere' => $matiere,
            'societe' => $societe,
            'prixList' => $prixList,
        ]);
    }

    /**
     * Affiche le formulaire d'édition d'une matière
     */
    public function edit(Matiere $matiere)
    {
        // Récupérer les données nécessaires pour le formulaire
        $familles = Famille::all();
        $sousFamilles = SousFamille::where('famille_id', $matiere->sousFamille->famille_id)->get();
        $unites = Unite::all();
        $dossier_standards = DossierStandard::all();
        $standards = [];
        $versions = [];

        if ($matiere->standardVersion) {
            $standards = Standard::where('dossier_standard_id', $matiere->standardVersion->standard->dossier_standard_id)->get();
            $versions = StandardVersion::where('standard_id', $matiere->standardVersion->standard_id)->get();
        }

        $materials = Material::all();

        return view('matieres.edit', compact(
            'matiere',
            'familles',
            'sousFamilles',
            'unites',
            'dossier_standards',
            'standards',
            'versions',
            'materials'
        ));
    }

    /**
     * Met à jour les données d'une matière
     */
    public function update(Request $request, Matiere $matiere)
    {
        // Validation des données
        dd($request->all());
        if ($matiere->isLocked()) {
            // Si la matière est verrouillée, seuls certains champs sont modifiables
            $validated = $request->validate([
                'sous_famille_id' => 'required|exists:sous_familles,id',
                'ref_valeur_unitaire' => 'nullable',
                'standard_id' => 'nullable|exists:standards,nom',
                'standard_version' => 'nullable|exists:standard_versions,version',
            ]);
        } else {
            // Sinon, tous les champs sont modifiables
            $validated = $request->validate([
                'ref_interne' => 'required|string|max:255',
                'designation' => 'required|string|max:255',
                'sous_famille_id' => 'required|exists:sous_familles,id',
                'unite_id' => 'required|exists:unites,id',
                'ref_valeur_unitaire' => 'nullable',
                'dn' => 'nullable|string|max:255',
                'epaisseur' => 'nullable|string|max:255',
                'standard_id' => 'nullable|exists:standards,nom',
                'standard_version' => 'nullable|exists:standard_versions,version',
                'stock_min' => 'required|numeric|min:0',
                'material_id' => 'nullable|exists:materials,id',
            ]);
        }
        if ($request->input('standard_version')) {
            if ($request->input('standard_version') === '' || $request->input('standard_id') === '') {
                $standard_version_id = null;
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
        } else {
            $standard_version_id = null;
        }
        $validated['standard_version_id'] = $standard_version_id;
        // Traiter correctement le champ ref_valeur_unitaire (comme dans quickStore)
        if ($request->input('ref_valeur_unitaire') === '' || $request->input('ref_valeur_unitaire') === 'non') {
            $validated['ref_valeur_unitaire'] = null;
        }

        // Si la matière est verrouillée, limiter les champs modifiables
        if ($matiere->isLocked()) {
            $allowedFields = Matiere::EDITABLE;
            $updateData = array_intersect_key($validated, array_flip($allowedFields));
        } else {
            $updateData = $validated;
        }

        $matiere->update($updateData);

        return redirect()->route('matieres.show', $matiere->id)
            ->with('success', 'Matière mise à jour avec succès');
    }

    public function mouvements($id, Request $request)
    {
        $matiere = Matiere::with('mouvementStocks')->findOrFail($id);
        // Validation des filtres
        $request->validate([
            'periode' => 'nullable|in:today,week,month,3months,6months,year,custom',
            'user_id' => 'nullable|exists:users,id',
            'type' => 'nullable|in:entree,sortie',
            'date_debut' => 'nullable|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
        ]);

        // Construction de la requête avec filtres
        $query = $matiere->mouvementStocks();

        // Filtre par période
        if ($request->filled('periode')) {
            $periode = $request->input('periode');

            switch ($periode) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->where('created_at', '>=', now()->startOfWeek());
                    break;
                case 'month':
                    $query->where('created_at', '>=', now()->startOfMonth());
                    break;
                case '3months':
                    $query->where('created_at', '>=', now()->subMonths(3));
                    break;
                case '6months':
                    $query->where('created_at', '>=', now()->subMonths(6));
                    break;
                case 'year':
                    $query->where('created_at', '>=', now()->startOfYear());
                    break;
                case 'custom':
                    if ($request->filled('date_debut')) {
                        $query->whereDate('created_at', '>=', $request->input('date_debut'));
                    }
                    if ($request->filled('date_fin')) {
                        $query->whereDate('created_at', '<=', $request->input('date_fin'));
                    }
                    break;
            }
        }

        // Filtre par utilisateur
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        // Filtre par type
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        // Récupérer les mouvements paginés
        $mouvements = $query->orderBy('created_at', 'desc')->paginate(20);

        // Récupérer la liste des utilisateurs qui ont fait des mouvements sur cette matière
        $utilisateurs = \App\Models\User::whereIn('id', function ($query) use ($matiere) {
            $query->select('user_id')
                ->from('mouvement_stocks')
                ->where('matiere_id', $matiere->id)
                ->distinct();
        })->orderBy('first_name')->orderBy('last_name')->get();

        // Conserver les paramètres de filtrage dans la pagination
        $mouvements->appends($request->query());

        return view('matieres.mouvements', compact('matiere', 'mouvements', 'utilisateurs'));
    }

    public function storeFournisseur(Request $request, $matiere_id)
    {
        // Validation des données
        $request->validate([
            'societe_id' => 'required|exists:societes,id',
            'ref_externe' => 'nullable|string|max:255',
        ]);

        try {
            // Vérifier que la matière existe
            $matiere = Matiere::findOrFail($matiere_id);

            // Vérifier que la société est bien un fournisseur
            $societe = Societe::whereIn('societe_type_id', ['2', '3'])->findOrFail($request->societe_id);

            // Vérifier si la relation n'existe pas déjà
            $existingRelation = SocieteMatiere::where('societe_id', $request->societe_id)
                ->where('matiere_id', $matiere_id)
                ->first();

            if ($existingRelation) {
                return redirect()
                    ->back()
                    ->with('error', 'Ce fournisseur est déjà associé à cette matière.');
            }

            // Créer la relation société-matière
            SocieteMatiere::create([
                'societe_id' => $request->societe_id,
                'matiere_id' => $matiere_id,
                'ref_externe' => $request->ref_externe,
            ]);

            return redirect()
                ->route('matieres.show', $matiere_id)
                ->with('success', 'Fournisseur ajouté avec succès à la matière.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'ajout du fournisseur', [
                'matiere_id' => $matiere_id,
                'societe_id' => $request->societe_id,
                'exception' => $e->getMessage()
            ]);

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de l\'ajout du fournisseur.');
        }
    }

}
