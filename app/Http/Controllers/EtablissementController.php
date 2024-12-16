<?php

namespace App\Http\Controllers;

use App\Models\Commentaire;
use App\Models\Etablissement;
use App\Models\Pays;
use App\Models\Societe;
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
            'societe' => $societe,
            'societes' => $societes,
            'pays' => $pays,
        ]);
    }
}
