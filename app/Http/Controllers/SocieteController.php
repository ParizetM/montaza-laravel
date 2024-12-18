<?php

namespace App\Http\Controllers;

use App\Models\Societe;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use App\Models\SocieteType;
use App\Models\Commentaire;
use App\Models\FormeJuridique;
use App\Models\CodeApe;
use App\Models\Etablissement;
use Illuminate\Http\RedirectResponse;

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


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('societes.create', [
            'societeTypes' => SocieteType::all(),
            'formeJuridiques' => FormeJuridique::all(),
            'codeApes' => CodeApe::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'raison_sociale' => 'required|string',
                'societe_type_id' => 'required|exists:societe_types,id',
                'forme_juridique_id' => 'required|exists:forme_juridiques,id',
                'code_ape_id' => 'required|exists:code_apes,id',
                'telephone' => 'required|string|max:30',
                'email' => 'required|email',
                'site_web' => 'nullable|string',
                'siren' => 'required|digits:9|unique:societes',
                'numero_tva' => 'required|string|min:13|max:13',
                'commentaire' => 'nullable|string',
            ],
            [
                'raison_sociale.required' => 'La raison sociale est obligatoire',
                'raison_sociale.string' => 'La raison sociale doit être une chaîne de caractères',
                'societe_type_id.required' => 'Le type de société est obligatoire',
                'societe_type_id.exists' => 'Le type de société est invalide',
                'forme_juridique_id.required' => 'La forme juridique est obligatoire',
                'forme_juridique_id.exists' => 'La forme juridique est invalide',
                'code_ape_id.required' => 'Le code APE est obligatoire',
                'code_ape_id.exists' => 'Le code APE est invalide',
                'telephone.required' => 'Le numéro de téléphone est obligatoire',
                'telephone.string' => 'Le numéro de téléphone doit être une chaîne de caractères',
                'telephone.max' => 'Le numéro de téléphone ne doit pas dépasser 30 caractères',
                'email.required' => 'L\'adresse email est obligatoire',
                'email.email' => 'L\'adresse email n\'est pas valide',
                'site_web.string' => 'L\'adresse du site web n\'est pas valide',
                'siren.required' => 'Le numéro SIREN est obligatoire',
                'siren.int' => 'Le numéro SIREN doit être un nombre',
                'siren.unique' => 'Le numéro SIREN est déjà utilisé',
                'siren.min' => 'Le numéro SIREN doit contenir au moins 9 caractères',
                'siren.max' => 'Le numéro SIREN doit contenir au maximum 9 caractères',
                'numero_tva.required' => 'Le numéro de TVA intracommunautaire est obligatoire',
                'numero_tva.string' => 'Le numéro de TVA intracommunautaire doit être une chaîne de caractères',
                'numero_tva.min' => 'Le numéro de TVA intracommunautaire doit contenir 13 caractères',
                'numero_tva.max' => 'Le numéro de TVA intracommunautaire doit contenir 13 caractères',
                'commentaire.string' => 'Le commentaire doit être une chaîne de caractères',
            ]
        );
        if ($request->site_web == 'http://') {
            $request->merge(['site_web' => null]);
        }
        if ($request->site_web == 'https://') {
            $request->merge(['site_web' => null]);
        }
        if ($request->site_web == 'http://www.') {
            $request->merge(['site_web' => null]);
        }
        if ($request->site_web == 'https://www.') {
            $request->merge(['site_web' => null]);
        }
        if (strpos($request->site_web, 'https://') === 0) {
            $request->merge(['site_web' => substr($request->site_web, 8)]);
        }
        if ($request->filled('commentaire')) {
            $commentaire = new Commentaire();
            $commentaire->contenu = $request->commentaire;
            $commentaire->save();
        } else {
            $commentaire = new Commentaire();
            $commentaire->contenu = '';
            $commentaire->save();
        }
        $societe = new Societe();
        $societe->raison_sociale = $request->raison_sociale;
        $societe->societe_type_id = $request->societe_type_id;
        $societe->forme_juridique_id = $request->forme_juridique_id;
        $societe->code_ape_id = $request->code_ape_id;
        $societe->telephone = $request->telephone;
        $societe->email = $request->email;
        $societe->site_web = $request->site_web;
        $societe->siren = $request->siren;
        $societe->numero_tva = $request->numero_tva;
        $societe->commentaire_id = $commentaire->id;
        $societe->save();
        Cache::flush(); // Clear the cache after saving a new Societe



        return redirect()->route('societes.index')->with('success', 'Société créée avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show(Societe $societe, Etablissement $etablissement = null): View|RedirectResponse
    {
        if ($etablissement == null) {
            $etablissement = $societe->etablissements->first();
        } else if ($etablissement->societe_id != $societe->id) {
            return redirect()->route('societes.etablissement.show', ['societe' => $etablissement->societe_id, 'etablissement' => $etablissement->id]);
        } else {
            $etablissement = Etablissement::find($etablissement->id);
        }

        return view('societes.show', [
            'societe' => $societe,
            'etablissement' => $etablissement,
        ]);
    }

    public function showJson(Societe $societe)
    {
        return response()->json($societe);
    }
    public function showEtablissementsJson(Societe $societe)
    {
        return response()->json($societe->etablissements);
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Societe $societe)
    {
        return view('societes.edit', [
            'societe' => $societe,
            'societeTypes' => SocieteType::all(),
            'formeJuridiques' => FormeJuridique::all(),
            'codeApes' => CodeApe::all(),
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Societe $societe)
    {
        $request->validate(
            [
                'raison_sociale' => 'required|string',
                'societe_type_id' => 'required|exists:societe_types,id',
                'forme_juridique_id' => 'required|exists:forme_juridiques,id',
                'code_ape_id' => 'required|exists:code_apes,id',
                'telephone' => 'required|string|max:30',
                'email' => 'required|email',
                'site_web' => 'nullable|string',
                'siren' => 'required|digits:9|unique:societes,siren,' . $societe->id,
                'numero_tva' => 'required|string|min:13|max:13',
                'commentaire' => 'nullable|string',
            ],
            [
                'raison_sociale.required' => 'La raison sociale est obligatoire',
                'raison_sociale.string' => 'La raison sociale doit être une chaîne de caractères',
                'societe_type_id.required' => 'Le type de société est obligatoire',
                'societe_type_id.exists' => 'Le type de société est invalide',
                'forme_juridique_id.required' => 'La forme juridique est obligatoire',
                'forme_juridique_id.exists' => 'La forme juridique est invalide',
                'code_ape_id.required' => 'Le code APE est obligatoire',
                'code_ape_id.exists' => 'Le code APE est invalide',
                'telephone.required' => 'Le numéro de téléphone est obligatoire',
                'telephone.string' => 'Le numéro de téléphone doit être une chaîne de caractères',
                'telephone.max' => 'Le numéro de téléphone ne doit pas dépasser 30 caractères',
                'email.required' => 'L\'adresse email est obligatoire',
                'email.email' => 'L\'adresse email n\'est pas valide',
                'siren.required' => 'Le numéro SIREN est obligatoire',
                'siren.int' => 'Le numéro SIREN doit être un nombre',
                'siren.unique' => 'Le numéro SIREN est déjà utilisé',
                'siren.min' => 'Le numéro SIREN doit contenir au moins 9 caractères',
                'numero_tva.required' => 'Le numéro de TVA intracommunautaire est obligatoire',
                'numero_tva.string' => 'Le numéro de TVA intracommunautaire doit être une chaîne de caractères',
                'numero_tva.min' => 'Le numéro de TVA intracommunautaire doit contenir 13 caractères',
                'numero_tva.max' => 'Le numéro de TVA intracommunautaire doit contenir 13 caractères',
                'commentaire.string' => 'Le commentaire doit être une chaîne de caractères',
            ]
        );
        if ($request->site_web == 'http://') {
            $request->merge(['site_web' => null]);
        }
        if ($request->site_web == 'https://') {
            $request->merge(['site_web' => null]);
        }
        if ($request->site_web == 'http://www.') {
            $request->merge(['site_web' => null]);
        }
        if ($request->site_web == 'https://www.') {
            $request->merge(['site_web' => null]);
        }
        if (strpos($request->site_web, 'https://') === 0) {
            $request->merge(['site_web' => substr($request->site_web, 8)]);
        }
        if ($request->filled('commentaire')) {
            $commentaire = new Commentaire();
            $commentaire->contenu = $request->commentaire;
            $commentaire->save();
        } else {
            $commentaire = new Commentaire();
            $commentaire->contenu = '';
            $commentaire->save();
        }
        $societe->raison_sociale = $request->raison_sociale;
        $societe->societe_type_id = $request->societe_type_id;
        $societe->forme_juridique_id = $request->forme_juridique_id;
        $societe->code_ape_id = $request->code_ape_id;
        $societe->telephone = $request->telephone;
        $societe->email = $request->email;
        $societe->site_web = $request->site_web;
        $societe->siren = $request->siren;
        $societe->numero_tva = $request->numero_tva;
        $societe->commentaire_id = $commentaire->id;
        $societe->save();
        Cache::flush(); // Clear the cache after saving a new Societe

        return redirect()->route('societes.show', ['societe' => $societe->id])->with('success', 'Société modifiée avec succès');
    }


    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(Societe $societe)
    // {
    //     //
    // }
}
