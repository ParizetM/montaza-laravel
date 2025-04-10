<?php

namespace App\Http\Controllers;

use App\Models\Cde;
use App\Models\CdeLigne;
use App\Models\Commentaire;
use App\Models\ConditionPaiement;
use App\Models\DdpCdeStatut;
use App\Models\Entite;
use App\Models\Famille;
use App\Models\Mailtemplate;
use App\Models\ModelChange;
use App\Models\Societe;
use App\Models\SocieteMatierePrix;
use App\Models\TypeExpedition;
use App\Models\Unite;
use App\Models\User;
use App\Services\StockService;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Mail;
use Response;
use Storage;

class CdeController extends Controller
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }
    public function indexColCdeSmall()
    {
        return $this->indexColCde(true);
    }
    public function indexColCde($isSmall = false)
    {
        $cdes = Cde::whereIn('ddp_cde_statut_id', [1, 2])->orderBy('ddp_cde_statut_id', 'asc')
            ->where('nom', '!=', 'undefined')
            ->take(7)->get();
        $cdes->load('user');
        $cdes->load('ddpCdeStatut');
        return view('ddp_cde.cde.index_col', compact('cdes', 'isSmall'));
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
        $code = $lastCde ? $lastCde->code : 'CDE-' . now()->format('y') . '-0000';
        $code = explode('-', $code);
        $code = $code[1] + 1;
        $commentaire_id = Commentaire::create([
            'contenu' => '',
        ])->id;
        $cde = Cde::create([
            'code' => 'undefined',
            'nom' => 'undefined',
            'ddp_cde_statut_id' => 1,
            'entite_id' => 1,
            'user_id' => Auth::id(),
            'tva' => 20,
            'type_expedition_id' => 1,
            'commentaire_id' => $commentaire_id,
        ]);
        $cdeid =  $cde->id;
        return redirect()->route('cde.show', $cdeid);
    }
    public function show($id)
    {
        $cde = Cde::findOrFail($id);
        if ($cde->ddpCdeStatut->id == 1) {
            $cdeid =  $cde->id;
            $familles = Famille::all();
            $unites = Unite::all();
            $entites = Entite::all();
            $societes = Societe::whereIn('societe_type_id', [2, 3])->get();
            $showRefFournisseur = $cde->show_ref_fournisseur;
            $entite_code = Entite::findOrFail($cde->entite_id)->id;
            $lastcode = CDE::where('code', 'LIKE', 'CDE-' . date('y') . '%')
                ->where('entite_id', $cde->entite_id)
                ->orderBy('code', 'desc')
                ->first();
            if ($entite_code == 1) {
                $entite_code = '';
            } elseif ($entite_code == 2) {
                $entite_code = 'AV';
            } elseif ($entite_code == 3) {
                $entite_code = 'AMB';
            }
            $lastNumber = $lastcode ? intval(explode('-', $lastcode->code)[2]) : 0;
            $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            if ($cde->code == 'undefined') {
                $cde->code = "CDE-" . date('y') . "-" . $newNumber . $entite_code;
                $cde->save();
            }
            return view(
                'ddp_cde.cde.create',
                [
                    'cde' => $cde,
                    'familles' => $familles,
                    'unites' => $unites,
                    'entites' => $entites,
                    'cdeid' => $cdeid,
                    'societes' => $societes,
                    'showRefFournisseur' => $showRefFournisseur,
                    'entite_code' => $entite_code,
                ]
            );
        } elseif ($cde->ddpCdeStatut->id == 2) {
            $cdeid =  $cde->id;
            $showRefFournisseur = $cde->show_ref_fournisseur;
            $typeExpedition = TypeExpedition::all()->pluck('short');
            $data = $this->getRetours($cdeid);
            return view('ddp_cde.cde.retours', compact('cde', ['data', 'showRefFournisseur', 'typeExpedition']));
        } elseif ($cde->ddpCdeStatut->id == 3 || $cde->ddpCdeStatut->id == 4 || $cde->ddpCdeStatut->id == 5) {
            $cdeid =  $cde->id;
            $showRefFournisseur = $cde->show_ref_fournisseur;
            $pdfcommande = $this->pdf($cdeid);
            return view('ddp_cde.cde.show', compact('cde', ['showRefFournisseur', 'pdfcommande']));
        }
    }

    public function save(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'cde_id' => 'required|integer|exists:cdes,id',
            'entite_id' => 'required|integer|exists:entites,id',
            'code' => 'required|string|max:4',
            'show_ref_fournisseur' => 'required|boolean',
            'total_ht' => 'required|numeric|min:0',
            'contact_id' => 'required|integer|exists:societe_contacts,id',
            'nom' => 'required|string|max:255',
            'matieres' => 'required|array',
            'matieres.*.id' => 'nullable|integer',
            'matieres.*.quantite' => 'required|numeric|min:0',
            'matieres.*.refInterne' => 'nullable|string|max:255',
            'matieres.*.refFournisseur' => 'nullable|string|max:255',
            'matieres.*.designation' => 'required|string|max:255',
            'matieres.*.prix' => 'required|numeric|min:0',
            // 'matieres.*.unite_id' => 'required|integer|exists:unites,id',
            'matieres.*.date' => 'nullable|date',
            'matieres.*.ligne_autre_id' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $entite_code = Entite::findOrFail($request->entite_id)->id;
        if ($entite_code == 1) {
            $entite_code = '';
        } elseif ($entite_code == 2) {
            $entite_code = 'AV';
        } elseif ($entite_code == 3) {
            $entite_code = 'AMB';
        }
        if ($request->code && ctype_digit($request->code)) {
            $code = str_pad($request->code, 4, '0', STR_PAD_LEFT);
        } else {
            response()->json(['error' => 'Invalid code format'], 400);
        }
        $cde = Cde::findOrFail($request->input('cde_id'));
        $cde->entite_id = $request->input('entite_id');
        $cde->societe_contact_id = $request->input('contact_id');
        $cde->show_ref_fournisseur = $request->input('show_ref_fournisseur');
        $cde->nom = $request->input('nom');
        $cde->total_ht = $request->input('total_ht');
        $cde->code = "CDE-" . date('y') . "-" . $code . $entite_code;
        $cde->save();
        $poste = 1;
        $cde->cdeLignes()->delete();
        foreach ($request->input('matieres') as $matiere) {
            if (isset($matiere['ligne_autre_id'])) {
                $cde->cdeLignes()->updateOrCreate(
                    ['ligne_autre_id' => $matiere['ligne_autre_id']],
                    [
                        'poste' => $poste++,
                        'quantite' => $matiere['quantite'],
                        'ref_interne' => $matiere['refInterne'] ?? null,
                        'ref_fournisseur' => $matiere['refFournisseur'] ?? null,
                        'designation' => $matiere['designation'] ?? null,
                        'prix_unitaire' => $matiere['prix'],
                        'prix' => $matiere['prix'] * $matiere['quantite'],
                        'date_livraison' => $matiere['date'] ?? null,
                    ]
                );
            } else {
                $cde->cdeLignes()->updateOrCreate([
                    'poste' => $poste++,
                    'matiere_id' => $matiere['id'],
                    'quantite' => $matiere['quantite'],
                    'ref_interne' => $matiere['refInterne'] ?? null,
                    'ref_fournisseur' => $matiere['refFournisseur'] ?? null,
                    'designation' => $matiere['designation'] ?? null,
                    'prix_unitaire' => $matiere['prix'],
                    'prix' => $matiere['prix'] * $matiere['quantite'],
                    // 'unite_id' => $matiere['unite_id'],
                    'date_livraison' => $matiere['date'] ?? null,
                ]);
            }
        }
        return response()->json(['success' => true]);
    }
    public function destroy($id): RedirectResponse
    {

        $cde = Cde::findOrFail($id);
        if ($cde->ddp_id != null) {
            $dppid = $cde->ddp_id;
            $cde->delete();
            return redirect()->route('ddp.show', $dppid);
        } else {
            $cde->delete();
            return redirect()->route('ddp_cde.index');
        }
    }
    public function validation($id): View
    {
        $cde = Cde::findOrFail($id);
        $users = User::all();
        $entite = Entite::where('id', $cde->entite_id)->first();
        $showRefFournisseur = $cde->show_ref_fournisseur;
        $typesExpedition = TypeExpedition::all();
        $conditionsPaiement = ConditionPaiement::all();
        $societe_id = $cde->societeContact->societe->id;
        //verifier si
        if ($showRefFournisseur == true) {
            $listeChangement = [];
            foreach ($cde->cdeLignes as $ligne) {
                if ($ligne->ligne_autre_id == null) {
                    $ligne->prix = $ligne->prix_unitaire * $ligne->quantite;
                    $ligne->save();
                    $societe_matiere = $ligne->matiere->societeMatiere($societe_id);
                    $ref_externe = $societe_matiere->ref_externe ?? null;
                    if ($ligne->ref_fournisseur != null && $ligne->ref_fournisseur != '' && $ref_externe != null && $ligne->ref_fournisseur != $ref_externe) {
                        $listeChangement[] = [
                            'id' => $ligne->id,
                            'ref_interne' => $ligne->matiere->ref_interne,
                            'ref_fournisseur' => $ligne->ref_fournisseur,
                            'ref_externe' => $ref_externe,
                            'designation' => $ligne->designation,
                            'societe_matiere_id' => $societe_matiere->id
                        ];
                    }
                }
            }
        } else {
            $listeChangement = false;
        }
        return view('ddp_cde.cde.validation', compact('cde', ['users', 'entite', 'showRefFournisseur', 'typesExpedition', 'conditionsPaiement', 'listeChangement']));
    }
    public function reset($id): RedirectResponse
    {
        $cde = Cde::findOrFail($id);
        $cde->delete();
        return redirect()->route('cde.create');
    }
    public function validate(Request $request, $id)
    {
        $cde = Cde::findOrFail(id: $id);
        $request->validate([
            'numero_affaire' => 'nullable|string|max:255',
            'nom_affaire' => 'nullable|string|max:255',
            'numero_devis' => 'nullable|string|max:255',
            'affaire_suivi_par' => 'nullable|integer',
            'acheteur_id' => 'nullable|integer',
            'afficher_destinataire' => 'nullable',
            'tva' => 'required|numeric|min:0',
            'horaires' => 'nullable|string|max:255',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'code_postal' => 'required|string|max:10',
            'pays' => 'required|string|max:255',
            'type_expedition_id' => 'required|integer|exists:type_expeditions,id',
            'condition_paiement_id' => 'required|integer',
            'condition_paiement_text' => 'nullable|string|max:255',
            'frais_de_port' => 'nullable|numeric|min:0',
            'frais_divers' => 'nullable|numeric|min:0',
            'frais_divers_texte' => 'nullable|string|max:255',
            'enregistrer_changement' => 'nullable', // Ajout de la validation pour enregistrer changement de ref fournisseur
        ]);
        $type_expedition_id = $request->input('type_expedition_id');
        if ($type_expedition_id == 1) {
            $adresse['horaires'] = $request->input('horaires');
            $adresse['adresse'] = $request->input('adresse');
            $adresse['ville'] = $request->input('ville');
            $adresse['code_postal'] = $request->input('code_postal');
            $adresse['pays'] = $request->input('pays');
            $adresse = json_encode($adresse);
        } else {
            $adresse = null;
        }
        if ($request->input('condition_paiement_id') == 0) {

            if ($request->input('condition_paiement_text') == null || $request->input('condition_paiement_text') == '') {
                return back()->with('error', 'Veuillez saisir une condition de paiement');
            }
            $condition_paiement = ConditionPaiement::create([
                'nom' => $request->input('condition_paiement_text')
            ]);
            $condition_paiement_id = $condition_paiement->id;
        } else {
            $condition_paiement_id = $request->input('condition_paiement_id');
        }
        $societe = $cde->societeContact->etablissement->societe;
        $societe->condition_paiement_id = $condition_paiement_id;
        $societe->save();
        $cde->affaire_numero = $request->input('numero_affaire') ?? null;
        $cde->affaire_nom = $request->input('nom_affaire') ?? null;
        $cde->devis_numero = $request->input('numero_devis') ?? null;
        $cde->affaire_suivi_par_id = $request->input('affaire_suivi_par') ?? null;
        $cde->acheteur_id = $request->input('acheteur_id') ?? null;
        $cde->afficher_destinataire = $request->input('afficher_destinataire') ? true : false;
        $cde->tva = $request->input('tva');
        $cde->adresse_livraison = $adresse;
        $cde->type_expedition_id = $request->input('type_expedition_id');
        $cde->condition_paiement_id = $condition_paiement_id;
        $cde->frais_de_port = $request->input('frais_de_port') ?? null;
        $cde->frais_divers = $request->input('frais_divers') ?? null;
        $cde->frais_divers_texte = $request->input('frais_divers_texte') ?? null;
        $cde->save();
        foreach ($cde->cdeLignes as $ligne) {
            $ligne->ddp_cde_statut_id = 2;
            $ligne->type_expedition_id = $request->input('type_expedition_id');
            $ligne->save();
        }
        if ($request->enregistrer_changement && $cde->show_ref_fournisseur == true) {
            $societe_id = $societe->id;
            foreach ($cde->cdeLignes as $ligne) {
                $ligne->prix = $ligne->prix_unitaire * $ligne->quantite;
                $ligne->save();
                $societe_matiere = $ligne->matiere->societeMatiere($societe_id);
                $ref_externe = $societe_matiere->ref_externe ?? null;
                if ($ligne->ref_fournisseur != null && $ligne->ref_fournisseur != '' && $ligne->ref_fournisseur != $ref_externe) {
                    $societe_matiere->ref_externe = $ligne->ref_fournisseur;
                    $societe_matiere->save();
                }
            }
        }
        $pdf = $this->pdf($cde->id);
        $mailtemplate = Mailtemplate::where('nom', 'cde')->first();
        $mailtemplate->sujet = str_replace('{code_cde}', $cde->code, $mailtemplate->sujet);
        return view('ddp_cde.cde.pdf_preview', compact('cde', ['pdf', 'mailtemplate']));
    }
    public function cancelValidate($id)
    {
        $cde = Cde::findOrFail($id);
        $cde->ddp_cde_statut_id = 1;
        $cde->save();
        return redirect()->route('cde.show', $cde->id);
    }
    public function pdf($cde_id, $sans_prix = false)
    {
        $cde = Cde::findOrFail($cde_id)->load('cdeLignes', 'cdeLignes.matiere');
        $contacts = $cde->societeContact;
        $lignes = $cde->cdeLignes;
        $etablissement = $contacts->etablissement;
        $afficher_destinataire = $cde->afficher_destinataire;
        $fileName = $cde->code . '.pdf';
        $entite = $cde->entite;
        $showRefFournisseur = $cde->show_ref_fournisseur;
        $total_ht = $cde->total_ht + $cde->frais_de_port + $cde->frais_divers;
        $total_ttc = $total_ht * (1 + ($cde->tva / 100));
        $cde->total_ttc = $total_ttc;
        $cde->save();
        $pdf = app('dompdf.wrapper');
        $pdf->loadView(
            'ddp_cde.cde.pdf',
            [
                'etablissement' => $etablissement,
                'contact' => $contacts,
                'cde' => $cde,
                'lignes' => $lignes,
                'afficher_destinataire' => $afficher_destinataire,
                'entite' => $entite,
                'showRefFournisseur' => $showRefFournisseur,
                'total_ttc' => $total_ttc,
                'total_ht' => $total_ht,
                'sans_prix' => $sans_prix,
            ]
        );
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
        if ($sans_prix) {
            $fileName = 'sans_prix_' . $fileName;
        }
        Storage::put('CDE/' . $year . '/' . $fileName, $pdf->output());
        return $fileName;
    }
    public function showPdf($cde, $dossier, $path)
    {
        $path = 'CDE/' . $dossier . '/' . $path;
        $file = Storage::get($path);
        $type = 'application/pdf';
        $response = Response::make($file, 200);
        $response->header('Content-Type', $type);
        return $response;
    }
    public function pdfDownloadSansPrix($id)
    {
        $cde = Cde::findOrFail($id);
        $pdf = $this->pdf($cde->id, true);
        $cdeAnnee = explode('-', $cde->code)[1];
        $pdfPath = 'CDE/' . $cdeAnnee . '/sans_prix_' . $cde->code . '.pdf';
        if (!Storage::exists($pdfPath)) {
            return redirect()->back()->with('error', 'PDF file not found');
        }

        return response()->download(storage_path('app/private/' . $pdfPath));
    }
    public function downloadPdfs($cde_id)
    {
        $cde = Cde::findOrFail($cde_id);
        $cdeAnnee = explode('-', $cde->code)[1];
        $pdfPath = 'CDE/' . $cdeAnnee . '/' . $cde->code . '.pdf';

        if (!Storage::exists($pdfPath)) {
            return redirect()->back()->with('error', 'Aucun fichier à télécharger');
        }

        return response()->download(storage_path('app/private/' . $pdfPath));
    }
    public function sendMails(Request $request, $id)
    {
        $cde = Cde::findOrFail($id);
        $request->validate([
            'sujet' => 'required|string|max:255',
            'contenu' => 'required|string',
        ]);
        $contenu = str_replace("CHEVRON-GAUCHE", "<", $request->contenu);
        $contenu = str_replace("CHEVRON-DROIT", ">", $contenu);
        $cdeAnnee = explode('-', $cde->code)[1];
        $pdfFileName = "{$cde->code}.pdf";
        $pdfPath = storage_path("app/private/CDE/{$cdeAnnee}/{$pdfFileName}");

        if (!file_exists($pdfPath)) {
            return response()->json(['error' => 'PDF file not found'], 404);
        }

        $contact = $cde->societeContact;

        try {
            Mail::send([], [], function ($message) use ($request, $contact, $pdfPath, $contenu) {
                $message->to($contact->email)
                    ->subject($request->sujet)
                    ->html($contenu)
                    ->attach($pdfPath);
            });
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while sending the email', 'message' => $e->getMessage()], 500);
        }

        $logmail = [];
        $logmail['sujet'] = $request->sujet;
        $logmail['contenu'] = $contenu;
        $logmail['Destinataire'] = $contact->email;
        $logmail['pdf'] = $pdfPath;
        $logmail['cde_nom'] = $cde->nom;
        $logmail['cde_id'] = $cde->id;
        $logmail['societe_raison_sociale'] = $cde->societe->raison_sociale;
        $logmail['societe_id'] = $cde->societe->id;
        $logmail['contact_nom'] = $contact->nom;
        $logmail['contact_id'] = $contact->id;

        ModelChange::create([
            'user_id' => Auth::id(),
            'model_type' => 'Commentaire',
            'before' => '',
            'after' => $logmail,
            'event' => 'creating',
        ]);

        $cde->ddp_cde_statut_id = 2;
        $cde->save();

        return redirect()->route('cde.show', $cde->id)->with('success', 'L\'email a été envoyé avec succès');
    }

    public function skipMails($id)
    {
        $cde = Cde::findOrFail($id);
        $cde->ddp_cde_statut_id = 2;
        $cde->save();
        return redirect()->route('cde.show', $cde->id);
    }

    public function getRetours($id)
    {
        $cde = Cde::findOrFail($id);
        $data = $cde->cdeLignes->map(function ($ligne) {
            return [
                $ligne->ddpCdeStatut->nom,
                $ligne->quantite,
                $ligne->prix_unitaire,
                $ligne->typeExpedition->short,
                $ligne->date_livraison_reelle ? Carbon::parse($ligne->date_livraison_reelle)->format('d/m/Y') : null,
            ];
        });
        return $data;
    }
    public function saveRetours($id, Request $request)
    {
        $cde = Cde::findOrFail($id);
        $data = array_map('str_getcsv', array_filter(explode("\r\n", $request->data), 'strlen'));
        $compteur = 0;
        foreach ($cde->cdeLignes as $ligne) {
            $ligne->ddp_cde_statut_id = DdpCdeStatut::where('nom', $data[$compteur][0])->first()->id ?? $ligne->ddp_cde_statut_id;
            $ligne->quantite = $data[$compteur][1];
            $ligne->prix_unitaire = $data[$compteur][2];
            $ligne->type_expedition_id = TypeExpedition::where('short', $data[$compteur][3])->first()->id ?? $ligne->type_expedition_id;
            $ligne->date_livraison_reelle = $data[$compteur][4] ? Carbon::createFromFormat('d/m/Y', $data[$compteur][4]) : null;
            $ligne->save();
            $compteur++;
            $data2[] = $ligne;
        }

        return $data2;
    }
    public function uploadAr($id, Request $request)
    {
        $cde = Cde::findOrFail($id);
        $request->validate([
            'accuse_reception' => 'required|file|mimes:pdf',
        ]);
        $cdeAnnee = explode('-', $cde->code)[1];
        $pdfFileName = "AR-{$cde->code}.pdf";
        $pdfPath = storage_path("private/CDE/{$cdeAnnee}/{$pdfFileName}");
        $pdfPath = $request->file('accuse_reception')->storeAs("CDE/{$cdeAnnee}", $pdfFileName);
        $cde->accuse_reception = $pdfFileName;
        $cde->save();
        return response()->json(['success' => true]);
    }

    public function terminer($id)
    {
        $cde = Cde::findOrFail($id);
        $cde->ddp_cde_statut_id = 3;
        $total_ht = 0;
        foreach ($cde->cdeLignes as $ligne) {
            if ($ligne->date_livraison_reelle && $ligne->ddp_cde_statut_id != 4) {
                $total_ht += $ligne->prix_unitaire * $ligne->quantite;
            }
        }
        // dd($total_ht_table);
        $cde->total_ht = $total_ht + $cde->frais_de_port + $cde->frais_divers;
        $cde->save();
        return redirect()->route('cde.show', $cde->id);
    }
    public function annulerTerminer($id)
    {
        $cde = Cde::findOrFail($id);
        $cde->ddp_cde_statut_id = 2;
        $cde->save();
        return redirect()->route('cde.show', $cde->id);
    }
    public function terminerControler($id)
    {

        $cde = Cde::findOrFail($id);
        $cde->ddp_cde_statut_id = 5;
        $societe = $cde->societeContact->etablissement->societe;
        foreach ($cde->cdeLignes as $ligne) {
            $matiere = $ligne->matiere;
            if ($ligne->date_livraison_reelle && $ligne->ddp_cde_statut_id != 4 && $ligne->ligne_autre_id == null) {
                $this->stockService->stock(
                    $matiere->id,
                    'entree',
                    $ligne->quantite,
                    null,
                    'Livraison commande - ' . $cde->code
                );
                $societe_matiere = $matiere->societeMatieres()->firstOrCreate(['societe_id' => $societe->id]);
                $newPrix = $ligne->prix_unitaire;
                if ($matiere->getLastPrice($societe->id) == null || $matiere->getLastPrice($societe->id)->prix_unitaire != $ligne->prix_unitaire) {
                    SocieteMatierePrix::updateOrCreate(
                        [
                            'societe_matiere_id' => $societe_matiere->id,
                            'cde_ligne_id' => $ligne->id,
                        ],
                        [
                            'prix_unitaire' => $newPrix ?? null,
                            'date' => now(),
                        ]
                    );
                }
            }
        }
        $cde->save();
        return redirect()->route('cde.show', $cde->id);
    }
    /**
     * Retourne le prochain code de CDE
     * @param mixed $entite_id
     * @return string
     */
    public function getLastCode($entite_id)
    {
        $entite = Entite::findOrFail($entite_id);
        $lastcode = CDE::where('code', 'LIKE', 'CDE-' . date('y') . '%')
            ->where('entite_id', $entite->id)
            ->orderBy('code', 'desc')
            ->first();
        $lastnumber = $lastcode ? intval(substr($lastcode->code, 8, 4)) : '0000';
        $lastnumber = str_pad($lastnumber + 1, 4, '0', STR_PAD_LEFT);
        if ($entite->id == 1) {
            $entite_code = '';
        } elseif ($entite->id == 2) {
            $entite_code = 'AV';
        } elseif ($entite->id == 3) {
            $entite_code = 'AMB';
        }
        return response()->json(['code' => $lastnumber, 'entite_code' => $entite_code]);
    }

    public function updateCommentaire(Request $request, $id)
    {
        $cde = Cde::find($id);
        if ($cde) {
            // Trouve le commentaire lié à la commande
            $commentaire = $cde->commentaire;
            if ($commentaire) {
            if ($commentaire->contenu == $request->commentaire) {
                return response()->json(['message' => 'Commentaire inchangé'], 200);
            }
            // Met à jour le commentaire avec la nouvelle valeur
            $commentaire->contenu = $request->commentaire;
            $commentaire->save();
            return response()->json(['message' => 'Commentaire mis à jour avec succès'], 200);
            } else {
            // Si la commande n'a pas encore de commentaire, on en crée un
            $commentaire = new Commentaire();
            $commentaire->contenu = $request->commentaire;
            $cde->commentaire()->save($commentaire);
            return response()->json(['message' => 'Commentaire créé avec succès'], 201);
            }
        }
    }
}
