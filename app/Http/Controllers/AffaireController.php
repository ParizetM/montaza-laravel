<?php

namespace App\Http\Controllers;

use App\Models\Affaire;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AffaireController extends Controller
{
    /**
     * Affiche la liste des affaires.
     */
    public function index(Request $request): View
    {
        // Validation des filtres
        $request->validate([
            'search' => 'nullable|string|max:255',
            'nombre' => 'nullable|integer|min:1|max:10000',
        ]);

        $nombre = intval($request->input('nombre', 50));
        $search = $request->input('search');

        // Construction de la requête avec filtres
        $query = Affaire::query();

        // Appliquer la recherche
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'LIKE', "%{$search}%")
                    ->orWhere('nom', 'LIKE', "%{$search}%");
            });
        }

        // Récupérer les affaires paginées
        $affaires = $query->orderBy('created_at', 'desc')->paginate($nombre);

        return view('affaires.index', [
            'affaires' => $affaires,
        ]);
    }

    /**
     * Affiche le formulaire de création d'une affaire.
     */
    public function create(): View
    {
        $code = Affaire::generateNextCode();
        return view('affaires.create', compact('code'));
    }

    /**
     * Enregistre une nouvelle affaire.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:affaires,code',
            'nom' => 'required|string|max:255',
            'budget' => 'nullable|numeric',
        ]);

        $affaire = Affaire::create([
            'code' => $request->input('code'),
            'nom' => $request->input('nom'),
            'budget' => $request->input('budget'),
        ]);
        $affaire->updateTotal(); // Met à jour le total HT de l'affaire
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'affaire' => [
                    'id' => $affaire->id,
                    'code' => $affaire->code,
                    'nom' => $affaire->nom,
                    'created_at' => $affaire->created_at->format('d/m/Y'),
                ],
            ]);
        }

        return redirect()->route('affaires.show', $affaire->id)
            ->with('success', 'Affaire créée avec succès');
    }

    /**
     * Affiche une affaire spécifique.
     */
    public function show(Affaire $affaire): View
    {
        // Vérification si l'affaire existe
        if (!$affaire) {
            abort(404, 'Affaire non trouvée');
        }
        return view('affaires.show', compact('affaire'));
    }

    /**
     * Affiche le formulaire d'édition d'une affaire.
     */
    public function edit(Affaire $affaire): View
    {
        // Vérification si l'affaire existe
        $affaire = Affaire::findOrFail($affaire->id ?? $affaire->id ?? $affaire);
        return view('affaires.edit', compact('affaire'));
    }

    /**
     * Met à jour une affaire spécifique.
     */
    public function update(Request $request, Affaire $affaire)
    {
        $request->validate([
            'code' => 'required|string|max:255|unique:affaires,code,' . $affaire->id,
            'nom' => 'required|string|max:255',
            'budget' => 'nullable|numeric',
        ]);

        $affaire->update([
            'code' => $request->input('code'),
            'nom' => $request->input('nom'),
            'budget' => $request->input('budget'),
        ]);
        $affaire->updateTotal(); // Met à jour le total HT de l'affaire
        return redirect()->route('affaires.show', $affaire->id)
            ->with('success', 'Affaire mise à jour avec succès');
    }

    /**
     * Supprime une affaire spécifique.
     */
    public function destroy(Affaire $affaire, Request $request)
    {
        try {
            $nom = $affaire->nom;
            $affaire->delete();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true]);
            }

            return redirect()->route('affaires.index')
                ->with('success', "L'affaire \"{$nom}\" a été supprimée avec succès.");
        } catch (\Exception $e) {
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'error' => 'Erreur lors de la suppression.']);
            }
            return redirect()->back()
                ->with('error', 'Une erreur est survenue lors de la suppression de l\'affaire.');
        }
    }
}
