<?php

namespace App\Http\Controllers;

use App\Models\Mailtemplate;
use Illuminate\Http\Request;

class MailtemplateController extends Controller
{
    public function index()
    {
        $mailtemplates = Mailtemplate::all();
        return view('mailtemplates.index', compact('mailtemplates'));
    }
    public function edit($id)
    {
        $mailtemplate = Mailtemplate::findOrFail($id);
        return view('mailtemplates.edit', compact('mailtemplate'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'sujet' => 'required|string',
            'contenu' => 'required|string',
        ]);
        $mailtemplate = Mailtemplate::findOrFail($id);
        $contenu = str_replace("CHEVRON-GAUCHE", "<", $request->contenu);
        $contenu = str_replace("CHEVRON-DROIT", ">", $contenu);
        $mailtemplate->sujet = $request->sujet;
        $mailtemplate->contenu = $contenu;
        $mailtemplate->save();
        return redirect()->route('mailtemplates.edit', $id)->with('success', 'Modèle de mail mis à jour avec succès');
    }
}
