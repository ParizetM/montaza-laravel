<?php

namespace App\Http\Controllers;

use App\Models\Cde;
use App\Models\DdpCdeStatut;
use App\Models\Entite;
use App\Models\Famille;
use App\Models\Societe;
use App\Models\Unite;
use Auth;
use Illuminate\Http\Request;

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
        return CdeController::show($cdeid);
    }
    public function show($id) {
        $cde = Cde::findOrFail($id);
        if ($cde->nom == 'undefined') {
            $cdeid =  $cde->id;
            $familles = Famille::all();
            $unites = Unite::all();
            $entites = Entite::all();
            $societes = Societe::all();
            return view('ddp_cde.cde.create', ['cde' => $cde, 'familles' => $familles, 'unites' => $unites, 'entites' => $entites, 'cdeid' => $cdeid, 'societes' => $societes]);
        }
    }
}
