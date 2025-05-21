<?php

namespace App\Http\Controllers;

use App\Models\Commentaire;
use App\Models\Etablissement;
use App\Models\Pays;
use App\Models\Societe;
use App\Models\SocieteType;
use Cache;
use Illuminate\Http\Request;


class EtablissementController extends Controller
{
    public function updateCommentaire(Request $request, $id)
    {
        $etablissement = Etablissement::find($id);
        if ($etablissement) {
            // Trouve le commentaire lié à la société
            $commentaire = $etablissement->commentaire;

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
                $etablissement->commentaire()->save($commentaire);
            }
        }

        return response()->json(['message' => 'Commentaire mis à jour'], 200);
    }
    public function create(Societe $societe)
    {
        $societes = Societe::all();
        $pays = Pays::all();
        return view('societes.etablissements.create', [
            'societe_' => $societe,
            'societes' => $societes,
            'pays' => $pays,
        ]);
    }
    public function store(Request $request, Societe $societe)
    {
        $societe = Societe::find($request->societe_id);
        $request->validate(
            [
                'nom' => 'required|string',
                'adresse' => 'nullable|string',
                'ville' => 'nullable|string',
                'code_postal' => 'nullable|integer|max_digits:10',
                'region' => 'nullable|string',
                'pays_id' => 'required|exists:pays,id',
                'societe_id' => 'required|exists:societes,id',
                'siret' => 'nullable|string',
                'commentaire' => 'nullable|string',
            ],
            [
                'nom.required' => 'Le nom est obligatoire',
                'pays_id.required' => 'Le pays est obligatoire',
                'societe_id.required' => 'La société est obligatoire',
                'siret.required' => 'Le siret est obligatoire',
                'siret.string' => 'Le siret doit être une chaîne de caractères',
                'commentaire.string' => 'Le commentaire doit être une chaîne de caractères',
                'adresse.string' => 'L\'adresse doit être une chaîne de caractères',
                'ville.string' => 'La ville doit être une chaîne de caractères',
                'code_postal.integer' => 'Le code postal doit être un nombre',
                'code_postal.max' => 'Le code postal doit contenir 10 chiffres maximum',
                'pays_id.exists' => 'Le pays n\'existe pas',
                'societe_id.exists' => 'La société n\'existe pas',
            ]
        );
        if ($request->siret == null) {
            if ($societe->societe_type_id != 2) {
                return redirect()->back()->withErrors(['siret' => 'Le siret est obligatoire pour les societe de type clients']);
            }
        }
        $siret = str_replace([' ', "\u{A0}", '&nbsp;'], '', $request->siret);
        if (strlen($siret) != 14 && $siret != '') {
            return redirect()->back()->withErrors(['siret' => 'Le siret doit contenir 14 chiffres']);
        }
        if (!is_numeric($siret) && $siret != '') {
            return redirect()->back()->withErrors(['siret' => 'Le siret doit contenir uniquement des chiffres']);
        }
        if ($request->siret == '') {
            $siret = null;
        }
        $etablissement = new Etablissement();
        $etablissement->nom = $request->nom;
        if ($request->adresse) {
            $etablissement->adresse = $request->adresse;
        }
        if ($request->ville) {
            $etablissement->ville = $request->ville;
        }
        if ($request->code_postal) {
            $etablissement->code_postal = $request->code_postal;
        }
        if ($request->region) {
            $etablissement->region = $request->region;
        }
        if ($request->commentaire) {
            $commentaire = new Commentaire();
            $commentaire->contenu = $request->commentaire;
            $commentaire->save();
        } else {
            $commentaire = new Commentaire();
            $commentaire->contenu = '';
            $commentaire->save();
        }
        $etablissement->pay_id = $request->pays_id;
        $etablissement->societe_id = $request->societe_id;
        $etablissement->siret = $siret;
        $etablissement->commentaire_id = $commentaire->id;
        $etablissement->save();



        Cache::flush();
        return redirect()->route('societes.show', ['societe' => $request->societe_id, 'add_contact' => 1])->with('success', 'Etablissement créé');
    }
    public function edit(Societe $societe, Etablissement $etablissement)
    {
        if ($etablissement->societe_id != $societe->id) {
            return redirect()->route('societes.show', $societe->id)->with('error', 'Etablissement non trouvé');
        }
        $societe_types = SocieteType::all();
        $societes = Societe::all();
        $pays = Pays::all();
        return view('societes.etablissements.edit', [
            'etablissement' => $etablissement,
            'societes' => $societes,
            'pays' => $pays,
            'societe_types' => $societe_types,
        ]);
    }
    public function update(Request $request, Etablissement $etablissement)
    {
        $societe = $etablissement->societe;
        $request->validate(
            [
                'nom' => 'required|string',
                'adresse' => 'nullable|string',
                'ville' => 'nullable|string',
                'code_postal' => 'nullable|integer|max_digits:10',
                'region' => 'nullable|string',
                'pays_id' => 'required|exists:pays,id',
                'societe_id' => 'required|exists:societes,id',
                'siret' => 'nullable|string',
                'commentaire' => 'nullable|string',
            ],
            [
                'nom.required' => 'Le nom est obligatoire',
                'pays_id.required' => 'Le pays est obligatoire',
                'societe_id.required' => 'La société est obligatoire',
                'siret.required' => 'Le siret est obligatoire',
                'siret.string' => 'Le siret doit être une chaîne de caractères',
                'commentaire.string' => 'Le commentaire doit être une chaîne de caractères',
                'adresse.string' => 'L\'adresse doit être une chaîne de caractères',
                'ville.string' => 'La ville doit être une chaîne de caractères',
                'code_postal.integer' => 'Le code postal doit être un nombre',
                'code_postal.max' => 'Le code postal doit contenir 10 chiffres maximum',
                'pays_id.exists' => 'Le pays n\'existe pas',
                'societe_id.exists' => 'La société n\'existe pas',
            ]
        );

        if ($request->siret == null) {
            if ($societe->societe_type_id != 2) {
                return redirect()->back()->withErrors(['siret' => 'Le siret est obligatoire pour les societe de type clients']);
            }
        }
        $siret = str_replace([' ', "\u{A0}", '&nbsp;'], '', $request->siret);
        if (strlen($siret) != 14 && $siret != '') {
            return redirect()->back()->withErrors(['siret' => 'Le siret doit contenir 14 chiffres']);
        }
        if (!is_numeric($siret) && $siret != '') {
            return redirect()->back()->withErrors(['siret' => 'Le siret doit contenir uniquement des chiffres']);
        }

        $etablissement->nom = $request->nom;
        $etablissement->adresse = $request->adresse;
        $etablissement->ville = $request->ville;
        $etablissement->code_postal = $request->code_postal;
        $etablissement->region = $request->region;
        $etablissement->pay_id = $request->pays_id;
        $etablissement->societe_id = $request->societe_id;
        $etablissement->siret = $siret;

        if ($request->commentaire) {
            $commentaire = $etablissement->commentaire;
            if ($commentaire) {
                $commentaire->contenu = $request->commentaire;
                $commentaire->save();
            } else {
                $commentaire = new Commentaire();
                $commentaire->contenu = $request->commentaire;
                $etablissement->commentaire()->save($commentaire);
            }
        }

        $etablissement->save();
        $societe = Societe::find($request->societe_id);
        Cache::flush();
        return redirect()->route('societes.etablissement.show', [
            'societe' => $societe,
            'etablissement' => $etablissement,
        ])->with('success', 'Etablissement mis à jour');
    }
    public function destroy(Etablissement $etablissement)
    {
        $societe = $etablissement->societe;
        $etablissement->delete();
        Cache::flush();
        return redirect()->route('societes.show', $societe)->with('success', 'Etablissement supprimé');
    }
}
