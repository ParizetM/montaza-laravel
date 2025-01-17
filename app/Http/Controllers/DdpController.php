<?php

namespace App\Http\Controllers;

use App\Models\Ddp;
use App\Models\DdpCdeStatut;
use App\Models\DdpLigneFournisseur;
use App\Models\Famille;
use App\Models\Mailtemplate;
use App\Models\ModelChange;
use App\Models\Societe;
use App\Models\Unite;
use App\Models\User;
use Auth;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Mail;
use Response;
use Storage;

class DdpController extends Controller
{
    public function indexDdp_cde()
    {

        return view('ddp_cde.index');
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
        $query = Ddp::query()
            ->where('nom', '!=', 'undefined')
            ->when($search, function ($query, $search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('nom', 'LIKE', "%{$search}%")
                             ->orWhere('code', 'LIKE', "%{$search}%");
                });
            })
            ->when($statut, function ($query, $statut) {
                $query->where('ddp_cde_statut_id', $statut);
            })
            ->orWhereHas('user', function ($subQuery) use ($search) {
                $subQuery->where('first_name', 'LIKE', "%{$search}%")
                         ->orWhere('last_name', 'LIKE', "%{$search}%");
            })
            ->orderBy('ddp_cde_statut_id', 'asc')
            ->orderBy('created_at', 'desc');

        // Récupérer les résultats paginés
        $ddps = $query->paginate($quantite);

        // Récupérer les statuts pour le filtre
        $ddp_statuts = DdpCdeStatut::all();

        // Retourner la vue avec les données
        return view('ddp_cde.ddp.index', compact('ddps', 'ddp_statuts'));
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
        if ($ddp->ddp_cde_statut_id == 2) {
            $ddp_societes = $ddp->ddpLigne->map(function ($ligne) {
                return $ligne->ddpLigneFournisseur->map(function ($fournisseur) {
                    return $fournisseur->societe;
                });
            })->flatten()->unique('id');
            $ddp->load('ddpLigne.matiere', 'ddpLigne.ddpLigneFournisseur.societe', 'ddpLigne.ddpLigneFournisseur.societeContact');
            return view('ddp_cde.ddp.show', compact('ddp', ['ddp_societes',]));
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
        $newCode = 'DDP-' . now()->format('y') . '-' . str_pad($code, 4, '0', STR_PAD_LEFT);
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
    public function validation($id): View|RedirectResponse
    {
        $ddp = Ddp::findOrFail($id)->load('ddpLigne', 'ddpLigne.ddpLigneFournisseur');
        if ($ddp->nom == 'undefined') {
            return redirect()->route('ddp.show', $ddp->id)->with('error', 'Veuillez renseigner la demande de prix');
        }
        $ddp_societe = $ddp->ddpLigne->map(function ($ligne) {
            return $ligne->ddpLigneFournisseur->map(function ($fournisseur) {
                return $fournisseur->societe;
            });
        })->flatten()->unique('id');
        $users = User::all();
        return view('ddp_cde.ddp.validation', ['ddp' => $ddp, 'societes' => $ddp_societe, 'users' => $users]);
    }
    public function validate($ddp, Request $request): View
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
        if ($request->dossier_suivi_par_id != 0) {
            $ddp->dossier_suivi_par_id = $request->dossier_suivi_par_id;
        }
        $ddp->afficher_destinataire = $request->afficher_destinataire ? 1 : 0;
        $ddp->save();
        $ddpannee = explode('-', $ddp->code)[1];
        DdpController::pdf($ddp->id);

        $pdfs = Storage::files('DDP/' . $ddpannee);
        $pdfs = array_filter($pdfs, function ($file) use ($ddp) {
            return strpos(basename($file), $ddp->code) === 0;
        });
        $pdfs = array_map(function ($file) use ($ddpannee) {
            return str_replace('DDP/' . $ddpannee . '/', '', $file);
        }, $pdfs);

        if (count($pdfs) != $ddp->societeContacts()->count()) {
            foreach ($pdfs as $pdf) {
                Storage::delete('DDP/' . $ddpannee . '/' . $pdf);
            }
            DdpController::pdf($ddp->id);
            $pdfs = Storage::files('DDP/' . $ddpannee);
            $pdfs = array_filter($pdfs, function ($file) use ($ddp) {
                return strpos(basename($file), $ddp->code) === 0;
            });
            $pdfs = array_map(function ($file) use ($ddpannee) {
                return str_replace('DDP/' . $ddpannee . '/', '', $file);
            }, $pdfs);
        }
        $mailtemplate = Mailtemplate::where('nom', 'ddp')->first();
        $mailtemplate->sujet = str_replace('{code_ddp}', $ddp->code, $mailtemplate->sujet);
        return view('ddp_cde.ddp.pdf_preview', ['ddp' => $ddp, 'pdfs' => $pdfs, 'mailtemplate' => $mailtemplate]);
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
            $afficher_destinataire = $ddp->afficher_destinataire;
            $destinataire = $contacts->email;
            $fileName = $ddp->code . '_' . $etablissement->societe->raison_sociale . '.pdf';
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('ddp_cde.ddp.pdf', ['etablissement' => $etablissement, 'ddp' => $ddp, 'lignes' => $lignes, 'afficher_destinataire' => $afficher_destinataire, 'destinataire' => $destinataire]);
            $pdf->setOption(['isRemoteEnabled' => true, 'isHtml5ParserEnabled' => true, 'isPhpEnabled' => true]);
            $pdf->output();
            $domPdf = $pdf->getDomPDF();
            $canvas = $domPdf->get_canvas();
            $canvas->page_text(
                $canvas->get_width() / 2 - 25,
                $canvas->get_height() - 18,
                "Page {PAGE_NUM} sur {PAGE_COUNT}",
                null,
                8,
                [0, 0, 0]
            );

        $year = now()->format('y');
            Storage::put('DDP/' . $year . '/' . $fileName, $pdf->output());
            $pdf = null;
        }
    }
    public function pdfshow($ddp, $dossier, $path)
    {
        $path = 'DDP/' . $dossier . '/' . $path;
        $file = Storage::get($path);
        $type = 'application/pdf';
        $response = Response::make($file, 200);
        $response->header('Content-Type', $type);
        return $response;
    }
    public function pdfsDownload($ddp_id)
    {
        $ddp = Ddp::findOrFail($ddp_id);
        $ddpannee = explode('-', $ddp->code)[1];
        $pdfs = Storage::files('DDP/' . $ddpannee);
        $pdfs = array_filter($pdfs, function ($file) use ($ddp) {
            return strpos(basename($file), $ddp->code) === 0;
        });
        $pdfs = array_map(function ($file) use ($ddpannee) {
            return str_replace('DDP/' . $ddpannee . '/', '', $file);
        }, $pdfs);
        if (count($pdfs) == 0) {
            return redirect()->route('ddp.show', $ddp->id)->with('error', 'Aucun fichier à télécharger');
        }
        if (count($pdfs) == 1) {
            return response()->download(storage_path('app/private/DDP/' . $ddpannee . '/' . $pdfs[0]));
        }
        $zip = new \ZipArchive();
        $zipFileName = 'DDP_' . $ddp->code . '.zip';
        $tempDir = storage_path('app/private/temp');
        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }
        $zip->open($tempDir . '/' . $zipFileName, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);
        foreach ($pdfs as $pdf) {
            $filePath = storage_path('app/private/DDP/' . $ddpannee . '/' . $pdf);
            if (file_exists($filePath)) {
                $zip->addFile($filePath, $pdf);
            }
        }
        $zip->close();
        return response()->download(storage_path('app/private/temp/' . $zipFileName))->deleteFileAfterSend(true);
    }
    public function sendMails(Request $request, $id)
    {
        $ddp = Ddp::findOrFail($id);
        $request->validate([
            'sujet' => 'required|string|max:255',
            'contenu' => 'required|string',
        ]);
        $contenu = str_replace("CHEVRON-GAUCHE", "<", $request->contenu);
        $contenu = str_replace("CHEVRON-DROIT", ">", $contenu);
        $ddpannee = explode('-', $ddp->code)[1];
        $pdfs = Storage::files("DDP/{$ddpannee}");
        $pdfs = array_filter($pdfs, function ($file) use ($ddp) {
            return strpos(basename($file), $ddp->code) === 0;
        });
        $pdfs = array_map(function ($file) use ($ddpannee) {
            return str_replace("DDP/{$ddpannee}/", '', $file);
        }, $pdfs);
        $contacts_Already_sent = [];
        foreach ($ddp->ddpLigne as $ligne) {
            foreach ($ligne->ddpLigneFournisseur as $fournisseur) {
                $societe = $fournisseur->societe;
                $contact = $fournisseur->societeContact;
                $pdfFileName = "{$ddp->code}_{$societe->raison_sociale}.pdf";
                $pdfPath = storage_path("app/private/DDP/{$ddpannee}/{$pdfFileName}");

                if (file_exists($pdfPath) && !in_array($contact->id, $contacts_Already_sent)) {
                    try {
                        Mail::send([], [], function ($message) use ($request, $contact, $pdfPath, $contenu) {
                            $message->to($contact->email)
                                ->subject($request->sujet)
                                ->html($contenu)
                                ->attach($pdfPath);
                        });
                    } catch (\Exception $e) {
                        return response()->json(['error' => 'An error occurred while sending emails', 'message' => $e->getMessage()], 500);
                    }
                    $logmail = [];
                    $logmail['sujet'] = $request->sujet;
                    $logmail['contenu'] = $contenu;
                    $logmail['Destinataire'] = $contact->email;
                    $logmail['pdf'] = $pdfPath;
                    $logmail['ddp_nom'] = $ddp->nom;
                    $logmail['ddp_id'] = $ddp->id;
                    $logmail['societe_raison_sociale'] = $societe->raison_sociale;
                    $logmail['societe_id'] = $societe->id;
                    $logmail['contact_nom'] = $contact->nom;
                    $logmail['contact_id'] = $contact->id;
                    ModelChange::create([
                        'user_id' => Auth::id(),
                        'model_type' => 'Commentaire',
                        'before' => '',
                        'after' => $logmail,
                        'event' => 'creating',
                    ]);
                    $contacts_Already_sent[] = $contact->id;
                }
            }
        }
        $ddp->ddp_cde_statut_id = 2;
        $ddp->save();
        return redirect()->route('ddp.show', $ddp->id)->with('success', 'Les emails ont été envoyés avec succès');
    }
    public function skipMails($id)
    {
        $ddp = Ddp::findOrFail($id);
        $ddp->ddp_cde_statut_id = 2;
        $ddp->save();
        return redirect()->route('ddp.show', $ddp->id);
    }
}
