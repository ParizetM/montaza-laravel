<?php

namespace App\Http\Controllers;

use App\Http\Resources\MatiereResource;
use App\Http\Resources\MatiereResourceWithPrice;
use App\MatiereMouvement;
use App\Models\DossierStandard;
use App\Models\Famille;
use App\Models\Matiere;
use App\Models\Societe;
use App\Models\SousFamille;
use App\Models\Standard;
use App\Models\StandardVersion;
use App\Models\Unite;
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
                $terms = explode(' ', $search);
                $query->where(function ($q) use ($terms) {
                if (count($terms) === 1) {
                    $q->where('designation', 'ILIKE', "%{$terms[0]}%")
                    ->orWhereHas('sousFamille', function ($subQuery) use ($terms) {
                        $subQuery->where('nom', 'ILIKE', "%{$terms[0]}%");
                    })
                    ->orWhere('ref_interne', 'ILIKE', "%{$terms[0]}%");
                }
                foreach ($terms as $term) {
                    $q->where(function ($subQuery) use ($term) {
                    if (stripos($term, 'dn') === 0) {
                        $value = substr($term, 2);
                        $subQuery->where('dn', 'ILIKE', "{$value}");
                    } elseif (stripos($term, 'ep') === 0) {
                        $value = str_replace([',', '.'], ['.', ','], substr($term, 2));
                        $subQuery->where('epaisseur', 'ILIKE', "{$value}");
                    } else {
                        $subQuery->where('designation', 'ILIKE', "%{$term}%")
                        ->orWhereHas('sousFamille', function ($subSubQuery) use ($term) {
                            $subSubQuery->where('nom', 'ILIKE', "%{$term}%");
                        })
                        ->orWhere('ref_interne', 'ILIKE', "%{$term}%");
                    }
                    });
                }
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
            $terms = explode(' ', $search);
            $query->where(function ($q) use ($terms) {
            if (count($terms) === 1) {
                $q->where('designation', 'ILIKE', "%{$terms[0]}%")
                ->orWhereHas('sousFamille', function ($subQuery) use ($terms) {
                    $subQuery->where('nom', 'ILIKE', "%{$terms[0]}%");
                })
                ->orWhere('ref_interne', 'ILIKE', "%{$terms[0]}%");
            }
            foreach ($terms as $term) {
                $q->where(function ($subQuery) use ($term) {
                if (stripos($term, 'dn') === 0) {
                    $value = substr($term, 2);
                    $subQuery->where('dn', 'ILIKE', "{$value}");
                } elseif (stripos($term, 'ep') === 0) {
                    $value = str_replace([',', '.'], ['.', ','], substr($term, 2));
                    $subQuery->where('epaisseur', 'ILIKE', "{$value}");
                } else {
                    $subQuery->where('designation', 'ILIKE', "%{$term}%")
                    ->orWhereHas('sousFamille', function ($subSubQuery) use ($term) {
                        $subSubQuery->where('nom', 'ILIKE', "%{$term}%");
                    })
                    ->orWhere('ref_interne', 'ILIKE', "%{$term}%");
                }
                });
            }
            });
        }

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
            ->get()
            ->unique('societe_id');
        $dates = $matiere->mouvements->isEmpty() ? null : $matiere->mouvements->pluck('created_at');
        $quantites = $matiere->mouvements->pluck('pivot.quantite');

        $quantitemouvement = 0;
        foreach ($matiere->mouvements as $mouvement) {
            $quantitemouvement += $mouvement->quantite  * ($mouvement->type_mouvement ? 1 : -1);
        }
        $quantiteActuelle = $matiere->quantite() - $quantitemouvement;
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

    // public function mouvement($matiere_id, float $quantite, bool $type) {
    //     $matiere = Matiere::findOrFail($matiere_id);
    //     if ($matiere->quantite() + $quantite * ($type ? 1 : -1) < 0) {
    //         return false;
    //     }
    //     $mouvement = new MatiereMouvement([
    //         'quantite' => $quantite,
    //         'type_mouvement' => $type,
    //     ]);
    //     $matiere->mouvements()->save($mouvement);
    //     $matiere->quantite() += $quantite * ($type ? 1 : -1);
    //     $matiere->save();
    //     return true;
    // }
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
        $matiere = Matiere::create([
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
