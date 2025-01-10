<?php

namespace App\Http\Controllers;

use App\Models\Ddp;
use App\Models\Famille;
use Auth;
use Illuminate\Http\Request;

class DdpController extends Controller
{
    public function indexDdp_cde()
    {

        return view('ddp_cde.index');
    }
    public function indexColDdp()
    {
        $ddps = Ddp::whereIn('ddp_cde_statut_id', [1, 2])->orderBy('ddp_cde_statut_id', 'asc')->take(7)->get();
        return view('ddp_cde.ddp.index_col', compact('ddps'));
    }
    public function show($id)
    {
        $ddp = Ddp::findOrFail($id);
        if ($ddp->ddp_cde_statut_id == 1) {
            $ddp->load('ddpLigne.matiere', 'ddpLigne.ddpLigneFournisseur.societe');
            $ddpid =  $ddp->id;
            $familles = Famille::all();
            return view('ddp_cde.ddp.create',['ddp' => $ddp, 'ddpid' => $ddpid,'familles' => $familles]);
        }
        return view('ddp_cde.ddp.show', compact('ddp'));
    }
    public function create()
    {
        Ddp::where('nom', 'undefined')->delete();
        $familles = Famille::all();
        $lastDdp = Ddp::latest()->first();
        $code = $lastDdp ? $lastDdp->code : 'DDP-' . now()->format('Y') . '-0000';
        $code = explode('-', $code);
        $code = $code[1] + 1;
        $newCode = 'DDP-' . now()->format('Y') . '-' . str_pad($code, 4, '0', STR_PAD_LEFT);
        $ddp = Ddp::create([
            'code' => $newCode,
            'nom' => 'undefined',
            'ddp_cde_statut_id' => 1,
            'user_id' => Auth::id(),
        ]);
        $ddpid =  $ddp->id;
        return view('ddp_cde.ddp.create', compact('familles', 'ddpid'));
    }
    public function save(Request $request) {
        $request->validate([
            'ddp_id' => 'required|integer|exists:ddps,id',
            'nom' => 'required|string|max:255',
            'matieres' => 'required|array',
            'matieres.*.id' => 'required|integer|exists:matieres,id',
            'matieres.*.quantity' => 'required|numeric|min:0',
            'matieres.*.fournisseurs' => 'required|array',
            'matieres.*.fournisseurs.*' => 'required|string|max:255',
        ]);
        $ddp = Ddp::findOrFail($request->ddp_id);
        $ddp->nom = $request->nom;
        $ddp->save();

        foreach ($request->matieres as $matiere) {
            $ddpLigne = $ddp->ddpLigne()->updateOrCreate(
            ['matiere_id' => $matiere['id']],
            ['quantite' => $matiere['quantity']]
            );

            foreach ($matiere['fournisseurs'] as $fournisseur) {
            $ddpLigne->ddpLigneFournisseur()->updateOrCreate(
                ['societe_id' => $fournisseur],
                [
                    'ddp_ligne_id' => $ddpLigne->id,
                    'ddp_cde_statut_id' => 1
                ]
            );
            }
        }

        return response()->json(['message' => 'Demande de prix sauvegardée avec succès']);
    }
}
