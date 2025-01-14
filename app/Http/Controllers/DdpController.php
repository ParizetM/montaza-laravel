<?php

namespace App\Http\Controllers;

use App\Models\Ddp;
use App\Models\DdpLigneFournisseur;
use App\Models\Famille;
use App\Models\Societe;
use App\Models\Unite;
use Auth;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View ;
use Response;
use Storage;

class DdpController extends Controller
{
    public function indexDdp_cde()
    {

        return view('ddp_cde.index');
    }
    public function indexColDdp()
    {
        $ddps = Ddp::whereIn('ddp_cde_statut_id', [1, 2])->orderBy('ddp_cde_statut_id', 'asc')
            ->where('nom', '!=', 'undefined')
            ->take(7)->get();
        $ddps->load('user');
        $ddps->load('ddpCdeStatut');

        return view('ddp_cde.ddp.index_col', compact('ddps'));
    }
    public function show($id)
    {
        $ddp = Ddp::findOrFail($id);
        if ($ddp->ddp_cde_statut_id == 1) {
            $ddp->load('ddpLigne.matiere', 'ddpLigne.ddpLigneFournisseur.societe');
            $ddpid =  $ddp->id;
            $familles = Famille::all();
            $unites = Unite::all();
            return view('ddp_cde.ddp.create', ['ddp' => $ddp, 'ddpid' => $ddpid, 'familles' => $familles, 'unites' => $unites]);
        }
        return view('ddp_cde.ddp.show', compact('ddp'));
    }
    public function create()
    {
        Ddp::where('nom', 'undefined')->delete();
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
        return DdpController::show($ddpid);
    }
    public function save(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'ddp_id' => 'required|integer|exists:ddps,id',
            'nom' => 'required|string|max:255',
            'matieres' => 'required|array',
            'matieres.*.id' => 'required|integer|exists:matieres,id',
            'matieres.*.quantity' => 'required|numeric|min:0',
            'matieres.*.fournisseurs' => 'required|array',
            'matieres.*.fournisseurs.*' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $ddp = Ddp::findOrFail($request->ddp_id);
            $ddp->nom = $request->nom;
            $ddp->save();
            $ddp->ddpLigne()->delete();
            foreach ($request->matieres as $matiere) {
                $ddpLigne = $ddp->ddpLigne()->updateOrCreate(
                    ['matiere_id' => $matiere['id']],
                    [ 'quantite' => $matiere['quantity']]
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
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while saving data', 'message' => $e->getMessage()], 500);
        }
    }
    public function destroy($id): RedirectResponse
    {

        $ddp = Ddp::findOrFail($id);
        $ddp->delete();
        return redirect()->route('ddp_cde.index');
    }
    public function validation($id): View
    {
        $ddp = Ddp::findOrFail($id)->load('ddpLigne', 'ddpLigne.ddpLigneFournisseur');
        $ddp_societe = $ddp->ddpLigne->map(function ($ligne) {
            return $ligne->ddpLigneFournisseur->map(function ($fournisseur) {
                return $fournisseur->societe;
            });
        })->flatten()->unique('id');
        return view('ddp_cde.ddp.validation', ['ddp' => $ddp, 'societes' => $ddp_societe]);
    }
    public function validate($ddp,Request $request): View
    {

        $ddp = Ddp::findOrFail($ddp)->load('ddpLigne', 'ddpLigne.ddpLigneFournisseur');
        foreach ($request->all() as $key => $value) {
            if ($key != '_token' && preg_match('/^contact-\d+$/', $key)) {
                $societe_id = explode('-', $key)[1];
                $ddpLigneFournisseurs = DdpLigneFournisseur::whereHas('ddpLigne', function ($query) use ($ddp) {
                    $query->where('ddp_id', $ddp->id);
                })
                ->where('societe_id', $societe_id)
                ->get();
                foreach ($ddpLigneFournisseurs as $ddpLigneFournisseur) {
                    $ddpLigneFournisseur->societe_contact_id = $value;
                    $ddpLigneFournisseur->save();
                }

            }
        }
        $ddp->save();
        $pdfs = Storage::files('DDP/'.now()->format('Y'));
        $pdfs = array_filter($pdfs, function($file) use ($ddp) {
            return strpos(basename($file), $ddp->code) === 0;
        });
        return view('ddp_cde.ddp.pdf_preview', ['ddp' => $ddp, 'pdfs' => $pdfs]);
    }
    public function pdf($ddpi_id)
    {
        $ddp = Ddp::findOrFail($ddpi_id)->load('ddpLigne', 'ddpLigne.ddpLigneFournisseur');
        $ddp_contacts = $ddp->ddpLigne->map(function ($ligne) {
            return $ligne->ddpLigneFournisseur->map(function ($fournisseur) {
            return $fournisseur->societeContact;
            });
        })->flatten()->unique('id');
        foreach ($ddp_contacts as $contacts) {
            $lignes = $ddp->ddpLigne->filter(function ($ligne) use ($contacts) {
                return $ligne->ddpLigneFournisseur->contains(function ($fournisseur) use ($contacts) {
                return $fournisseur->societe_contact_id == $contacts->id;
                });
            });
            $etablissement = $contacts->etablissement;
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('ddp_cde.ddp.pdf', ['etablissement' => $etablissement, 'ddp' => $ddp, 'lignes' => $lignes]);
            $pdf->setOption(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);

            $fileName = $ddp->code . '_' . $etablissement->societe->raison_sociale . '.pdf';
            $year = now()->format('Y');
            Storage::put('DDP/'.$year.'/' . $fileName, $pdf->output());
            $pdf = null;
        }
        return response()->json(['message' => 'PDFs generated successfully']);

    }
}
