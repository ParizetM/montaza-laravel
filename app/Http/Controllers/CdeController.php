<?php

namespace App\Http\Controllers;

use App\Models\Cde;
use App\Models\CdeLigne;
use App\Models\DdpCdeStatut;
use App\Models\Entite;
use App\Models\Famille;
use App\Models\Societe;
use App\Models\Unite;
use App\Models\User;
use Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CdeController extends Controller
{
    public function indexColCde()
    {
        $cdes = Cde::whereIn('ddp_cde_statut_id', [1, 2])->orderBy('ddp_cde_statut_id', 'asc')
            ->where('nom', '!=', 'undefined')
            ->take(7)->get();
        $cdes->load('user');
        $cdes->load('ddpCdeStatut');
        return view('ddp_cde.cde.index_col', compact('cdes'));
    }
    public function index(Request $request)
    {
        // Validation des entrées
        $request->validate([
            'search' => 'nullable|string|max:255',
            'statut' => 'nullable|integer|exists:ddp_cde_statuts,id',
            'nombre' => 'nullable|integer|min:1|'
        ]);
        // Lecture des entrées avec des valeurs par défaut
        $search = $request->input('search');
        $statut = $request->input('statut');
        $quantite = $request->input('nombre', 20);

        // Construire la requête de base
        $query = Cde::query()
            ->where('nom', '!=', 'undefined')
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('nom', 'ILIKE', "%{$search}%")
                        ->orWhere('code', 'ILIKE', "%{$search}%")
                        ->orWhereHas('user', function ($subQuery) use ($search) {
                            $subQuery->where('first_name', 'ILIKE', "%{$search}%")
                                ->orWhere('last_name', 'ILIKE', "%{$search}%");
                        });
                });
            })
            ->when($statut, function ($query, $statut) {
                $query->where('ddp_cde_statut_id', $statut);
            })
            ->orderBy('ddp_cde_statut_id', 'asc')
            ->orderBy('created_at', 'desc');

        // Récupérer les résultats paginés
        $cdes = $query->paginate($quantite);

        // Récupérer les statuts pour le filtre
        $cde_statuts = DdpCdeStatut::all();

        // Retourner la vue avec les données
        return view('ddp_cde.cde.index', compact('cdes', 'cde_statuts'));
    }

    public function create()
    {
        Cde::where('nom', 'undefined')->delete();
        $lastCde = Cde::latest()->first();
        $code = $lastCde ? $lastCde->code : 'DDP-' . now()->format('Y') . '-0000';
        $code = explode('-', $code);
        $code = $code[1] + 1;
        $newCode = 'DDP-' . now()->format('y') . '-' . str_pad($code, 4, '0', STR_PAD_LEFT);
        $cde = Cde::create([
            'code' => $newCode,
            'nom' => 'undefined',
            'ddp_cde_statut_id' => 1,
            'entite_id' => 1,
            'user_id' => Auth::id(),
            'tva' => 0,
            'type_expedition_id' => 1,
            'condition_paiement_id' => 1,
        ]);
        $cdeid =  $cde->id;
        return route('ddp_cde.show', $cdeid);
    }
    public function show($id)
    {
        $cde = Cde::findOrFail($id);
        if ($cde->ddpCdeStatut->id == 1) {
            $cdeid =  $cde->id;
            $familles = Famille::all();
            $unites = Unite::all();
            $entites = Entite::all();
            $societes = Societe::where('societe_type_id', [2, 3])->get();
            $showRefFournisseur = $cde->show_ref_fournisseur;
            return view(
                'ddp_cde.cde.create',
                [
                    'cde' => $cde,
                    'familles' => $familles,
                    'unites' => $unites,
                    'entites' => $entites,
                    'cdeid' => $cdeid,
                    'societes' => $societes,
                    'showRefFournisseur' => $showRefFournisseur
                ]
            );
        }
    }

    public function save(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'cde_id' => 'required|integer|exists:cdes,id',
            'entite_id' => 'required|integer|exists:entites,id',
            'show_ref_fournisseur' => 'required|boolean',
            'contact_id' => 'required|integer|exists:societe_contacts,id',
            'nom' => 'required|string|max:255',
            'matieres' => 'required|array',
            'matieres.*.id' => 'required|integer|exists:matieres,id',
            'matieres.*.quantite' => 'required|numeric|min:1',
            'matieres.*.refInterne' => 'nullable|string|max:255',
            'matieres.*.refFournisseur' => 'nullable|string|max:255',
            'matieres.*.designation' => 'nullable|string|max:255',
            'matieres.*.prix' => 'required|numeric|min:0',
            'matieres.*.unite_id' => 'required|integer|exists:unites,id',
            'matieres.*.date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $cde = Cde::findOrFail($request->input('cde_id'));
        $cde->entite_id = $request->input('entite_id');
        $cde->societe_contact_id = $request->input('contact_id');
        $cde->show_ref_fournisseur = $request->input('show_ref_fournisseur');
        $cde->nom = $request->input('nom');
        $cde->save();
        $poste = 1;
        $cde->cdeLignes()->delete();
        foreach ($request->input('matieres') as $matiere) {
            $cde->cdeLignes()->updateOrCreate([
                'poste' => $poste++,
                'matiere_id' => $matiere['id'],
                'quantite' => $matiere['quantite'],
                'ref_interne' => $matiere['refInterne'] ?? null,
                'ref_fournisseur' => $matiere['refFournisseur'] ?? null,
                'designation' => $matiere['designation'] ?? null,
                'prix_unitaire' => $matiere['prix'],
                'prix' => $matiere['prix'] * $matiere['quantite'],
                'unite_id' => $matiere['unite_id'],
                'date_livraison' => $matiere['date'] ?? null,
            ]);
        }
        return response()->json(['success' => true]);
    }
    public function destroy($id): RedirectResponse
    {

        $cde = Cde::findOrFail($id);
        $cde->delete();
        return redirect()->route('ddp_cde.index');
    }
    public function validation($id): View
    {
        $cde = Cde::findOrFail($id);
        $users = User::all();
        $entite = Entite::where('id', $cde->entite_id)->first();
        return view('ddp_cde.cde.validation', compact('cde',[ 'users', 'entite']));
    }
}
