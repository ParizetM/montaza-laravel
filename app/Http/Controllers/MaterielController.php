<?php

namespace App\Http\Controllers;

use App\Models\Materiel;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;
use Illuminate\Http\Request;

class MaterielController extends Controller
{
    public function index()
    {
        $materiels = Materiel::where('desactive', false)->latest()->get();
        return view('reparation.materiel.index', compact('materiels'));
    }

        public function create()
    {
        return view('reparation.materiel.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'reference' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'description' => 'nullable|string',
            'numero_serie' => 'nullable|string|max:255',
            'acquisition_date' => 'nullable|date',
        ]);

        // Si aucun numéro de série fourni, générer un identifiant unique pour éviter la contrainte unique
        $numeroSerie = $validatedData['numero_serie'] ?? null;
        if (empty($numeroSerie)) {
            $numeroSerie = 'AUTO-' . strtoupper(uniqid());
        }

        // Mapper les champs du formulaire vers les colonnes existantes (libellés en français avec accents)
        Materiel::create([
            'reference' => $validatedData['reference'],
            'designation' => $validatedData['designation'],
            'description' => $validatedData['description'] ?? null,
            'numero_serie' => $numeroSerie,
            // Si une date d'acquisition est fournie, l'utiliser ; sinon définir la date du jour
            'acquisition_date' => $validatedData['acquisition_date'] ?? now()->toDateString(),
        ]);

        return redirect()->route('reparation.materiel.index')->with('success', 'Matériel ajouté avec succès.');
    }

    public function edit(Materiel $materiel)
    {
        $materiels = Materiel::all();
        return view('reparation.materiel.create', compact('materiel'));
    }

    public function update(Request $request, Materiel $materiel)
    {
        $validatedData = $request->validate([
            'reference' => 'required|string|max:255',
            'designation' => 'required|string|max:255',
            'description' => 'nullable|string',
            'numero_serie' => 'nullable|string|max:255',
            'acquisition_date' => 'nullable|date',
        ]);

        // Mettre à jour les champs du matériel en utilisant les alias d'attributs du modèle
        $materiel->update([
            'reference' => $validatedData['reference'],
            'designation' => $validatedData['designation'],
            'description' => $validatedData['description'] ?? null,
            'numero_serie' => $validatedData['numero_serie'] ?? $materiel->numero_serie,
            'acquisition_date' => $validatedData['acquisition_date'] ?? $materiel->acquisition_date,
        ]);

        return redirect()->route('reparation.materiel.index')->with('success', 'Matériel mis à jour avec succès.');
    }

    public function destroy(Request $request, Materiel $materiel)
    {
        if (Schema::hasColumn('materiels', 'desactive')) {
            $materiel->desactive = true;
            $materiel->save();
        } else {
            $materiel->delete();
        }

        // Si la requête attend du JSON (AJAX via fetch), renvoyer une réponse JSON claire
        if ($request->expectsJson()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('reparation.materiel.index')->with('success', 'Matériel désactivé avec succès.');
    }
    public function historique()
    {
        $materiels = Materiel::where('desactive', true)->get();
        return view('reparation.materiel.historique', compact('materiels'));
    }
}
