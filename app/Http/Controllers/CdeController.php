<?php

namespace App\Http\Controllers;

use App\Models\Cde;
use App\Models\CdeLigne;
use App\Models\ConditionPaiement;
use App\Models\DdpCdeStatut;
use App\Models\Entite;
use App\Models\Famille;
use App\Models\Mailtemplate;
use App\Models\ModelChange;
use App\Models\Societe;
use App\Models\TypeExpedition;
use App\Models\Unite;
use App\Models\User;
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
        $newCode = 'CDE-' . now()->format('y') . '-' . str_pad($code, 4, '0', STR_PAD_LEFT);
        $cde = Cde::create([
            'code' => $newCode,
            'nom' => 'undefined',
            'ddp_cde_statut_id' => 1,
            'entite_id' => 1,
            'user_id' => Auth::id(),
            'tva' => 0,
            'type_expedition_id' => 1,
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
        } elseif ($cde->ddpCdeStatut->id == 2) {
            $cdeid =  $cde->id;
            $showRefFournisseur = $cde->show_ref_fournisseur;
            $typeExpedition = TypeExpedition::all()->pluck('short');
            $data = $this->getRetours($cdeid);
            return view('ddp_cde.cde.retours', compact('cde',['data','showRefFournisseur','typeExpedition']));
        } elseif ($cde->ddpCdeStatut->id == 3 || $cde->ddpCdeStatut->id == 4) {
            $cdeid =  $cde->id;
            $showRefFournisseur = $cde->show_ref_fournisseur;
            return view('ddp_cde.cde.show', compact('cde',['showRefFournisseur']));
        }
    }

    public function save(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'cde_id' => 'required|integer|exists:cdes,id',
            'entite_id' => 'required|integer|exists:entites,id',
            'show_ref_fournisseur' => 'required|boolean',
            'total_ht' => 'required|numeric|min:0',
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
        $cde->total_ht = $request->input('total_ht');
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
        $showRefFournisseur = $cde->show_ref_fournisseur;
        $typesExpedition = TypeExpedition::all();
        $conditionsPaiement = ConditionPaiement::all();
        return view('ddp_cde.cde.validation', compact('cde', ['users', 'entite', 'showRefFournisseur', 'typesExpedition', 'conditionsPaiement']));
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
    public function pdf($cde_id)
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
        $pdf->loadView('ddp_cde.cde.pdf', ['etablissement' => $etablissement, 'contact' => $contacts, 'cde' => $cde, 'lignes' => $lignes, 'afficher_destinataire' => $afficher_destinataire, 'entite' => $entite, 'showRefFournisseur' => $showRefFournisseur, 'total_ttc' => $total_ttc, 'total_ht' => $total_ht]);
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

    public function getRetours($id) {
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
    public function saveRetours($id, Request $request) {
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
    public function uploadAr($id, Request $request) {
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

    public function terminer($id) {
        $cde = Cde::findOrFail($id);
        $cde->ddp_cde_statut_id = 3;
        $total_ht = 0;
        foreach ($cde->cdeLignes as $ligne) {
            if ($ligne->date_livraison_reelle && $ligne->ddp_cde_statut_id != 4) {
            $matiere = $ligne->matiere;
            $matiereController = new MatiereController();
            $matiereController->mouvement($matiere->id, $ligne->quantite, true);
            $matiere->unite_id = $ligne->unite_id;
            $matiere->fournisseurs()->sync([
                $ligne->fournisseur_id => [
                    'ref_fournisseur' => $ligne->ref_fournisseur ?? null,
                    'prix' => $ligne->prix_unitaire * $ligne->quantite,
                    'societe_id' => $cde->societe->id,
                    'unite_id' => $ligne->unite_id,
                    'date_dernier_prix' => now(),
                    'cde_ligne_fournisseur_id' => $ligne->id,
                ]
            ]);
            $matiere->save();
            $total_ht += $ligne->prix_unitaire * $ligne->quantite;
            }
        }
        // dd($total_ht_table);
        $cde->total_ht = $total_ht + $cde->frais_de_port + $cde->frais_divers;
        $cde->save();
        return redirect()->route('cde.show', $cde->id);
    }
    public function annulerTerminer($id) {
        $cde = Cde::findOrFail($id);
        $cde->ddp_cde_statut_id = 2;
        $cde->save();
        return redirect()->route('cde.show', $cde->id);
    }
}
