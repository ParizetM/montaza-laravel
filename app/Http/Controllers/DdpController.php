<?php

namespace App\Http\Controllers;

use App\Models\Cde;
use App\Models\CdeLigne;
use App\Models\Ddp;
use App\Models\DdpCdeStatut;
use App\Models\DdpLigneFournisseur;
use App\Models\Entite;
use App\Models\Famille;
use App\Models\Mailtemplate;
use App\Models\Matiere;
use App\Models\ModelChange;
use App\Models\Societe;
use App\Models\SocieteContact;
use App\Models\Unite;
use App\Models\User;
use Auth;
use DB;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Mail;
use Response;
use Storage;

class DdpController extends Controller
{
    private function getExcelColumnName($index)
    {
        $letters = '';
        while ($index >= 0) {
            $letters = chr($index % 26 + 65) . $letters;
            $index = intval($index / 26) - 1;
        }
        return $letters;
    }
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
        $ddps = $query->paginate($quantite);

        // Récupérer les statuts pour le filtre
        $ddp_statuts = DdpCdeStatut::all();

        // Retourner la vue avec les données
        return view('ddp_cde.ddp.index', compact('ddps', 'ddp_statuts'));
    }

    public function indexColDdpSmall() {
        return DdpController::indexColDdp(true);
    }
    public function indexColDdp($isSmall = false)
    {
        $ddps = Ddp::whereIn('ddp_cde_statut_id', [1, 2])->orderBy('ddp_cde_statut_id', 'asc')
            ->where('nom', '!=', 'undefined')
            ->take(7)->get();
        $ddps->load('user');
        $ddps->load('ddpCdeStatut');

        return view('ddp_cde.ddp.index_col', compact('ddps','isSmall'));
    }
/*
 ######  ##     ##  #######  ##      ##
##    ## ##     ## ##     ## ##  ##  ##
##       ##     ## ##     ## ##  ##  ##
 ######  ######### ##     ## ##  ##  ##
      ## ##     ## ##     ## ##  ##  ##
##    ## ##     ## ##     ## ##  ##  ##
 ######  ##     ##  #######   ###  ###
*/
    public function show($id)
    {
        $ddp = Ddp::findOrFail($id);
        if ($ddp->ddp_cde_statut_id == 1) {
            $ddp->load('ddpLigne.matiere', 'ddpLigne.ddpLigneFournisseur.societe');
            $ddpid =  $ddp->id;
            $familles = Famille::all();
            $unites = Unite::all();
            $entites = Entite::all();
            return view('ddp_cde.ddp.create', ['ddp' => $ddp, 'ddpid' => $ddpid, 'familles' => $familles, 'unites' => $unites, 'entites' => $entites]);
        }
        if ($ddp->ddp_cde_statut_id == 2) {
            $ddp_societes = $ddp->ddpLigne->map(function ($ligne) {
                return $ligne->ddpLigneFournisseur->map(function ($fournisseur) {
                    return $fournisseur->societe;
                });
            })->flatten()->unique('id');
            $table_data = $this->getRetours($id);

            $ddp->load('ddpLigne.matiere', 'ddpLigne.ddpLigneFournisseur.societe', 'ddpLigne.ddpLigneFournisseur.societeContact');
            $data = [];
            // Première ligne : Noms des sociétés
            $row = [];
            foreach ($ddp_societes as $societe) {
                $row[] = $societe->raison_sociale;
                $row[] = $societe->raison_sociale;
                $row[] = $societe->raison_sociale;
                $row[] = $societe->raison_sociale;
            }
            $data[] = $row;

            // Lignes des données
            foreach ($table_data as $index => $row) {
                $row = array_map(function ($value) {
                    return $value;
                }, $row);
                $data[] = $row;
            }

            // Dernière ligne : Formules de calcul
            $row = [];
            $indexSociete = 0;
            while ($indexSociete < (count($ddp_societes) * 4)) {
                $colPrix = $this->getExcelColumnName($indexSociete + 1); // Colonne pour la somme
                $colunite = 'UNDEFINED';
                $colDate = $this->getExcelColumnName($indexSociete + 3); // Colonne pour le minimum
                $rowCount = count($ddp->ddpLigne) + 1;
                $row[] = "UNDEFINED";
                $row[] = "=SUM({$colPrix}2:{$colPrix}{$rowCount})";
                $row[] = $colunite;
                $row[] = "=IF(MINIFS({$colDate}2:{$colDate}{$rowCount}, {$colDate}2:{$colDate}{$rowCount}, \">=\" & TODAY())=0, \"\", MINIFS({$colDate}2:{$colDate}{$rowCount}, {$colDate}2:{$colDate}{$rowCount}, \">=\" & TODAY()))";

                $indexSociete += 4;
            }
            $data[] = $row;
            return view('ddp_cde.ddp.retours', compact('ddp', ['ddp_societes', 'data']));
        }
        if ($ddp->ddp_cde_statut_id == 3) {
            $ddp_societes = $ddp->ddpLigne->map(function ($ligne) {
                return $ligne->ddpLigneFournisseur->map(function ($fournisseur) {
                    return $fournisseur->societe;
                });
            })->flatten()->unique('id');
            $ddp_societe_contacts = $ddp->ddpLigne->map(function ($ligne) {
                return $ligne->ddpLigneFournisseur->map(function ($fournisseur) {
                    return $fournisseur->societeContact;
                });
            })->flatten()->unique('id');
            $table_data = $this->getRetours($id);

            $ddp->load('ddpLigne.matiere', 'ddpLigne.ddpLigneFournisseur.societe', 'ddpLigne.ddpLigneFournisseur.societeContact');
            $data = [];
            $row = [];
            // Lignes des données
            foreach ($table_data as $index => $row) {
                $row = array_map(function ($value) {
                    return $value;
                }, $row);
                $data[] = $row;
            }

            // Dernière ligne : Formules de calcul
            $row = [];
            $indexSociete = 0;
            while ($indexSociete < (count($ddp_societes) * 3)) {


                $sum = 0;
                foreach ($table_data as $dataRow) {
                    $sum += (float)$dataRow[$indexSociete];
                }
                $row[] = ($sum != 0) ? $sum . '€' : '';
                $sum = 0;
                foreach ($table_data as $dataRow) {
                    $sum += (float)$dataRow[$indexSociete + 1];
                }
                $row[] = ($sum != 0) ? $sum . '€' : '';

                $dates = array_filter(array_column($table_data, $indexSociete + 2));
                $closestDate = null;
                if (!empty($dates)) {
                    $closestDate = min(array_map(function ($date) {
                        return Carbon::hasFormat($date, 'd/m/Y') ? Carbon::createFromFormat('d/m/Y', $date) : null;
                    }, $dates));
                }
                $row[] = $closestDate ? $closestDate->format('d/m/Y') : '';

                $indexSociete++;
                $indexSociete++;
                $indexSociete++;
            }
            $data[] = $row;
            $ddplignes = $ddp->ddpLigne;
            return view('ddp_cde.ddp.show', compact('ddp', ['ddp_societes', 'data', 'ddplignes','ddp_societe_contacts']));
        }
    }
    public function create()
    {
        Ddp::where('nom', 'undefined')->delete();
        $lastDdp = Ddp::latest()->first();
        $code = $lastDdp ? $lastDdp->code : 'DDP-' . now()->format('y') . '-0000';
        $code = explode('-', $code);
        $code = $code[1] + 1;
        $newCode = 'DDP-' . now()->format('y') . '-' . str_pad($code, 4, '0', STR_PAD_LEFT);
        $ddp = Ddp::create([
            'code' => $newCode,
            'nom' => 'undefined',
            'ddp_cde_statut_id' => 1,
            'entite_id' => 1,
            'user_id' => Auth::id(),
        ]);
        $ddpid =  $ddp->id;
        return DdpController::show($ddpid);
    }
    public function save(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'ddp_id' => 'required|integer|exists:ddps,id',
            'entite_id' => 'required|integer|exists:entites,id',
            'nom' => 'required|string|max:255',
            'matieres' => 'required|array',
            'matieres.*.id' => 'required|integer|exists:matieres,id',
            'matieres.*.quantity' => 'required|numeric|min:0',
            'matieres.*.unite_id' => 'required|integer|exists:unites,id',
            'matieres.*.fournisseurs' => 'required|array',
            'matieres.*.fournisseurs.*' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        try {
            $ddp = Ddp::findOrFail($request->ddp_id);
            $ddp->entite_id = $request->entite_id;
            $ddp->nom = $request->nom;
            $ddp->save();
            $ddp->ddpLigne()->delete();
            foreach ($request->matieres as $matiere) {
                $ddpLigne = $ddp->ddpLigne()->updateOrCreate(
                    ['matiere_id' => $matiere['id']],
                    [
                        'quantite' => $matiere['quantity'],
                        'unite_id' => $matiere['unite_id'],
                        'ddp_id' => $ddp->id
                    ]
                );

                foreach ($matiere['fournisseurs'] as $fournisseur) {
                    $ddpLigne->ddpLigneFournisseur()->updateOrCreate(
                        ['societe_id' => $fournisseur],
                        [
                            'ddp_ligne_id' => $ddpLigne->id,
                            'ddp_cde_statut_id' => 1,
                            'unite_id' => $matiere['unite_id'],
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
        $entite = $ddp->entite;
        return view('ddp_cde.ddp.validation', ['ddp' => $ddp, 'societes' => $ddp_societe, 'users' => $users, 'entite' => $entite]);
    }
    public function validate($ddp, Request $request): View
    {

        $ddp = Ddp::findOrFail($ddp)->load('ddpLigne', 'ddpLigne.ddpLigneFournisseur');

        foreach ($request->all() as $key => $value) {
            if (preg_match('/^contact-\d+$/', $key)) {
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
        if (isset($request->date_rendu)) {
            $ddp->date_rendu = $request->date_rendu;
        } else {
            $ddp->date_rendu = null;
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
    public function cancelValidate($id): RedirectResponse
    {
        $ddp = Ddp::findOrFail($id);
        $ddp->ddp_cde_statut_id = 1;
        $ddp->save();
        foreach ($ddp->ddpLigne as $ddpLigne) {
            foreach ($ddpLigne->ddpLigneFournisseur as $ddpLigneFournisseur) {
                DB::table('societe_matiere')->where('ddp_ligne_fournisseur_id', $ddpLigneFournisseur->id)->delete();
            }
        }
        return redirect()->route('ddp.show', $ddp->id);
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
            $entite = $ddp->entite;
            $pdf = app('dompdf.wrapper');
            $pdf->loadView('ddp_cde.ddp.pdf', ['etablissement' => $etablissement, 'ddp' => $ddp, 'lignes' => $lignes, 'afficher_destinataire' => $afficher_destinataire, 'destinataire' => $destinataire,'entite' => $entite]);
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
            return redirect()->back()->with('error', 'Aucun fichier à télécharger');
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
    public function saveRetours(Request $request, $id)
    {
        $ddp = Ddp::findOrFail($id);
        $request->validate([
            'data' => 'required|string',
        ]);
        $data = array_map('str_getcsv', array_filter(explode("\r\n", $request->data), 'strlen'));
        array_shift($data);
        array_pop($data);
        $ddp_societes = $ddp->ddpLigne->map(function ($ligne) {
            return $ligne->ddpLigneFournisseur->map(function ($fournisseur) {
                return $fournisseur->societe;
            });
        })->flatten()->unique('id');
        $data2 = [];
        // foreach ($ddp->ddpLigne as $ddpLigne) {

        //     $row = [];
        //     foreach ($ddp_societes as $societe) {
        //     $row[] = '';
        //     $row[] = '';
        //     }
        //     $data2[] = $row;
        // }
        foreach ($ddp->ddpLigne as $index0 => $ddpLigne) {
            $row = [];
            foreach ($ddp_societes as $index1 => $societe) {
            $matiereid = $ddpLigne->matiere_id;
            $societeid = $societe->id;
            $matiere = Matiere::findOrNew($matiereid);
            $existingFournisseur = $matiere->fournisseurs()->where('societe_id', $societeid)->orderBy('date_dernier_prix', 'desc')->first();
            $ref_fournisseur = $data[$index0][$index1 * 4] ?? null;
            $newPrix = $data[$index0][$index1 * 4 + 1] ?? null;
            $newUnite = Unite::where('short', $data[$index0][$index1 * 4 + 2] ?? '')->first()->id ?? null;
            $dateString = $data[$index0][$index1 * 4 + 3] ?? '';
            $date = (!empty($dateString) && $dateString != 'UNDEFINED') ? Carbon::createFromFormat('d/m/Y', $dateString) : null;

            if ($newPrix && (!$existingFournisseur || $existingFournisseur->pivot->prix != $newPrix) && $newPrix != 'UNDEFINED' && $newPrix != '' && $newPrix ) {
                $matiere->fournisseurs()->attach($societeid, [
                'ref_fournisseur' => $ref_fournisseur,
                'prix' => $newPrix,
                'unite_id' => $newUnite,
                'date_dernier_prix' => now(),
                'ddp_ligne_fournisseur_id' => $ddpLigne->ddpLigneFournisseur->where('societe_id', $societeid)->first()->id
                ]);
            } elseif ($existingFournisseur && $newPrix != 'UNDEFINED' && $newPrix != '' && $newPrix) {
                $existingFournisseur->pivot->update([
                'ref_fournisseur' => $ref_fournisseur,
                'prix' => $newPrix,
                'unite_id' => $newUnite,
                'date_dernier_prix' => now()
                ]);
            }

            $ddpLigneFournisseur = $ddpLigne->ddpLigneFournisseur->where('societe_id', $societeid)->first();
            if ($ddpLigneFournisseur && $date) {
                $ddpLigneFournisseur->date_livraison = $date->format('Y-m-d');
                $ddpLigneFournisseur->save();
                $date = 'date_enregistrement';
            }

            $row[] = $ref_fournisseur;
            $row[] = $newPrix;
            $row[] = $newUnite;
            $row[] = $date;
            }
            $data2[] = $row;
        }
        return $data2;
        // Assuming you have some logic to save the data here

    }
    public function getRetours($id): array
    {
        $ddp = Ddp::findOrFail($id);
        $ddp_societes = $ddp->ddpLigne->map(function ($ligne) {
            return $ligne->ddpLigneFournisseur->map(function ($fournisseur) {
                return $fournisseur->societe;
            });
        })->flatten()->unique('id');
        if ($ddp->ddp_cde_statut_id == 2) {


            $data = [];
            foreach ($ddp->ddpLigne as $ddpLigne) {
                $row = [];
                foreach ($ddp_societes as $societe) {
                    $ddpLigneFournisseur = $ddpLigne->ddpLigneFournisseur->where('societe_id', $societe->id)->first();
                    if ($ddpLigneFournisseur) {
                        $prix = '';
                        $unite = '';
                        if ($ddpLigneFournisseur->ddpLigne->matiere) {
                            $fournisseur = $ddpLigneFournisseur->ddpLigne->matiere->fournisseurs()
                                ->where('societe_id', $societe->id)
                                ->where('ddp_ligne_fournisseur_id', $ddpLigneFournisseur->id)
                                ->orderBy('date_dernier_prix', 'desc')
                                ->first();
                            if ($fournisseur) {
                                $prix = $fournisseur->pivot->prix;
                                if ($fournisseur->pivot->unite_id) {
                                    $unite = Unite::find($fournisseur->pivot->unite_id)->short;
                                } elseif ($ddpLigneFournisseur->ddpLigne->unite_id) {
                                    $unite = $ddpLigneFournisseur->ddpLigne->unite->short;
                                } else {
                                    $unite = '';
                                }
                            } elseif ($ddpLigneFournisseur->ddpLigne->unite_id) {
                                $unite = $ddpLigneFournisseur->ddpLigne->unite->short;
                            } else {
                                $prix = '';
                                $unite = '';
                            }
                        } else {
                            $prix = '';
                            $unite = '';
                        }
                        $date_livraison = $ddpLigneFournisseur->date_livraison ? Carbon::parse($ddpLigneFournisseur->date_livraison)->format('d/m/Y') : '';
                        $reference_fournisseur = $ddpLigneFournisseur->ddpLigne->matiere->fournisseurs()
                            ->where('societe_id', $societe->id)
                            ->whereNotNull('ref_fournisseur')
                            ->where('ref_fournisseur', '!=', '')
                            ->orderBy('date_dernier_prix', 'desc')
                            ->first();
                        $reference_fournisseur = $reference_fournisseur ? $reference_fournisseur->pivot->ref_fournisseur : '';
                        // dd($ddpLigneFournisseur->ddpLigne->matiere->id,[$societe->id,$reference_fournisseur]);
                        $row[] = $reference_fournisseur ?? '';
                        $row[] = $prix;
                        $row[] = $unite;
                        $row[] = $date_livraison;
                    } else {
                        $row[] = 'UNDEFINED';
                        $row[] = 'UNDEFINED';
                        $row[] = 'UNDEFINED';
                        $row[] = 'UNDEFINED';
                    }
                }
                $data[] = $row;
            }
        } elseif ($ddp->ddp_cde_statut_id == 3) {
            $data = [];
            foreach ($ddp->ddpLigne as $ddpLigne) {
                $row = [];
                foreach ($ddp_societes as $societe) {
                    $ddpLigneFournisseur = $ddpLigne->ddpLigneFournisseur->where('societe_id', $societe->id)->first();
                    if ($ddpLigneFournisseur) {
                        $prix = '';
                        if ($ddpLigneFournisseur->ddpLigne->matiere) {
                            $fournisseur = $ddpLigneFournisseur->ddpLigne->matiere->fournisseurs()
                                ->where('societe_id', $societe->id)
                                ->where('ddp_ligne_fournisseur_id', $ddpLigneFournisseur->id)
                                ->orderBy('date_dernier_prix', 'desc')
                                ->first();
                            if ($fournisseur) {
                                $prix = $fournisseur->pivot->prix;
                                $unite = Unite::find($fournisseur->pivot->unite_id)->short ?? '';
                            } else {
                                $prix = '';
                                $unite = '';
                            }
                        } else {
                            $prix = '';
                            $unite = '';
                        }
                        $date_livraison = $ddpLigneFournisseur->date_livraison ? Carbon::parse($ddpLigneFournisseur->date_livraison)->format('d/m/Y') : '';
                        if ($prix != '' ) {
                            if ($unite != '') {
                                $row[] = $prix . ' €/' . $unite;
                            } else {
                                $row[] = $prix . ' €';
                            }
                            $row[] = $prix * $ddpLigne->quantite . ' €';
                        } else {
                            $row[] = '';
                            $row[] = '';
                        }
                        $row[] = $date_livraison;
                    } else {
                        $row[] = 'UNDEFINED';
                        $row[] = 'UNDEFINED';
                        $row[] = 'UNDEFINED';
                    }
                }
                $data[] = $row;
            }
        }
        return $data;
    }
    public function terminer($id)
    {
        $ddp = Ddp::findOrFail($id);
        $ddp->ddp_cde_statut_id = 3;
        $ddp->save();
        foreach ($ddp->ddpLigne as $ddpLigne) {
            foreach ($ddpLigne->ddpLigneFournisseur as $ddpLigneFournisseur) {
                $existingEntry = DB::table('societe_matiere')
                    ->where('ddp_ligne_fournisseur_id', $ddpLigneFournisseur->id)
                    ->orderBy('date_dernier_prix', 'desc')
                    ->first();

                if ($existingEntry) {
                    DB::table('societe_matiere')
                        ->where('id', '!=', $existingEntry->id)
                        ->where('ddp_ligne_fournisseur_id', $ddpLigneFournisseur->id)
                        ->delete();
                }
            }
        }
        return redirect()->route('ddp.show', $ddp->id);
    }
    public function annuler_terminer($id)
    {
        $ddp = Ddp::findOrFail($id);
        if ($ddp->ddp_cde_statut_id == 3) {
            $ddp->ddp_cde_statut_id = 2;
            $ddp->save();
        } else {
            return redirect()->route('ddp.show', $ddp->id)->with('error', 'Vous ne pouvez pas annuler une demande de prix qui n\'est pas terminée');
        }
        return redirect()->route('ddp.show', $ddp->id);
    }
    public function commander($id,$societe_contact_id) {
        $ddp = Ddp::findOrFail($id);
        $societe_contact = SocieteContact::findOrFail($societe_contact_id);
        $societe = $societe_contact->etablissement->societe;
        $cde = Cde::create([
            'code' => 'CDE-' . now()->format('y') . '-' . str_pad(Cde::whereYear('created_at', now()->year)->count() + 1, 4, '0', STR_PAD_LEFT),
            'nom' => 'Commande de ' . $ddp->code.' chez '.$societe->raison_sociale,
            'ddp_cde_statut_id' => 1,
            'ddp_id' => $ddp->id,
            'entite_id' => $ddp->entite_id,
            'societe_contact_id' => $societe_contact_id,
            'user_id' => Auth::id(),
            'tva' => 0,
            'type_expedition_id' => 1,
            'condition_paiement_id' => 1,
        ]);
        $poste = 0;
        foreach ($ddp->ddpLigne as $ddpLigne) {
            foreach ($ddpLigne->ddpLigneFournisseur as $ddpLigneFournisseur) {
                if ($ddpLigneFournisseur->societe_id == $societe->id) {
                    $poste++;
                    CdeLigne::create([
                        'cde_id' => $cde->id,
                        'poste' => $poste,
                        'matiere_id' => $ddpLigne->matiere_id,
                        'ref_interne' => $ddpLigne->matiere->ref_interne,
                        'designation' => $ddpLigne->matiere->designation,
                        'quantite' => $ddpLigne->quantite,
                        'ref_fournisseur' => $ddpLigne->matiere->getLastPrice($societe->id) ? $ddpLigne->matiere->getLastPrice($societe->id)->pivot->ref_fournisseur : null,
                        'prix_unitaire' => $ddpLigne->matiere->getLastPrice($societe->id) ? $ddpLigne->matiere->getLastPrice($societe->id)->pivot->prix : null,
                        'unite_id' => $ddpLigne->matiere->getLastPrice($societe->id) ? $ddpLigne->matiere->getLastPrice($societe->id)->pivot->unite_id : null,
                        'date_livraison' => $ddpLigneFournisseur->date_livraison,
                    ]);
                }
            }
        }
        return redirect()->route('cde.show', $cde->id);
    }
}
