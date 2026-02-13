<?php

namespace App\Http\Controllers;

use App\Models\Personnel;
use App\Models\PersonnelConge;
use Illuminate\Http\Request;

class PersonnelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        if (!is_string($search)) {
            $search = '';
        }

        $show_deleted = $request->input('show_deleted');
        if ($show_deleted) {
            $personnels = Personnel::onlyTrashed()->get();
            return view('personnel.index', ['personnels' => $personnels]);
        }

        // Rechercher des employés en fonction du terme de recherche
        $personnels = Personnel::query()
            ->when($search, function ($query, $search) {
                $query->where('nom', 'ILIKE', "%{$search}%")
                    ->orWhere('prenom', 'ILIKE', "%{$search}%")
                    ->orWhere('email', 'ILIKE', "%{$search}%")
                    ->orWhere('matricule', 'ILIKE', "%{$search}%")
                    ->orWhere('poste', 'ILIKE', "%{$search}%")
                    ->orWhere('departement', 'ILIKE', "%{$search}%");
            })
            ->orderBy('nom')
            ->orderBy('prenom')
            ->get();

        return view('personnel.index', ['personnels' => $personnels]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('personnel.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'matricule' => 'required|string|max:255|unique:personnels,matricule',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:personnels,email',
            'telephone' => 'nullable|string|max:20',
            'telephone_mobile' => 'nullable|string|max:20',
            'poste' => 'nullable|string|max:255',
            'departement' => 'nullable|string|max:255',
            'date_embauche' => 'nullable|date',
            'date_depart' => 'nullable|date|after_or_equal:date_embauche',
            'salaire' => 'nullable|numeric|min:0',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:10',
            'numero_securite_sociale' => 'nullable|string|max:50',
            'statut' => 'required|in:actif,en_conge,suspendu,parti',
            'notes' => 'nullable|string',
        ]);

        Personnel::create($validated);

        return redirect()->route('personnel.index')
            ->with('success', 'Employé créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Personnel $personnel)
    {
        $personnel->load(['affaires', 'conges' => function($query) {
            $query->orderBy('date_debut', 'desc');
        }]);
        return view('personnel.show', compact('personnel'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Personnel $personnel)
    {
        return view('personnel.edit', compact('personnel'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Personnel $personnel)
    {
        $validated = $request->validate([
            'matricule' => 'required|string|max:255|unique:personnels,matricule,' . $personnel->id,
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:personnels,email,' . $personnel->id,
            'telephone' => 'nullable|string|max:20',
            'telephone_mobile' => 'nullable|string|max:20',
            'poste' => 'nullable|string|max:255',
            'departement' => 'nullable|string|max:255',
            'date_embauche' => 'nullable|date',
            'date_depart' => 'nullable|date|after_or_equal:date_embauche',
            'salaire' => 'nullable|numeric|min:0',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'code_postal' => 'nullable|string|max:10',
            'numero_securite_sociale' => 'nullable|string|max:50',
            'statut' => 'required|in:actif,en_conge,suspendu,parti',
            'notes' => 'nullable|string',
        ]);

        $personnel->update($validated);

        return redirect()->route('personnel.index')
            ->with('success', 'Employé mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Personnel $personnel)
    {
        $personnel->delete();

        return redirect()->route('personnel.index')
            ->with('success', 'Employé supprimé avec succès.');
    }

    /**
     * Restore the specified resource.
     */
    public function restore($id)
    {
        $personnel = Personnel::onlyTrashed()->findOrFail($id);
        $personnel->restore();

        return redirect()->route('personnel.index')
            ->with('success', 'Employé restauré avec succès.');
    }

    /**
     * Ajoute un congé à un personnel
     */
    public function storeConge(Request $request, Personnel $personnel)
    {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'type' => 'required|in:conge_paye,conge_maladie,conge_sans_solde,autre',
            'motif' => 'nullable|string|max:1000',
            'statut' => 'nullable|in:demande,valide,refuse',
        ]);

        // Vérifier qu'il n'y a pas de chevauchement avec d'autres congés
        $dateDebut = $request->input('date_debut');
        $dateFin = $request->input('date_fin');

        $hasConflict = $personnel->conges()
            ->where(function($query) use ($dateDebut, $dateFin) {
                $query->where(function($q) use ($dateDebut, $dateFin) {
                    $q->where('date_debut', '<=', $dateFin)
                      ->where('date_fin', '>=', $dateDebut);
                });
            })
            ->exists();

        if ($hasConflict) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['date_debut' => 'Cette période chevauche un congé déjà existant.']);
        }

        $personnel->conges()->create([
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'type' => $request->input('type'),
            'motif' => $request->input('motif'),
            'statut' => $request->input('statut', 'valide'),
        ]);

        return redirect()->back()
            ->with('success', 'Le congé a été ajouté avec succès.');
    }

    /**
     * Met à jour un congé
     */
    public function updateConge(Request $request, Personnel $personnel, $congeId)
    {
        $request->validate([
            'date_debut' => 'required|date',
            'date_fin' => 'required|date|after_or_equal:date_debut',
            'type' => 'required|in:conge_paye,conge_maladie,conge_sans_solde,autre',
            'motif' => 'nullable|string|max:1000',
            'statut' => 'nullable|in:demande,valide,refuse',
        ]);

        $conge = $personnel->conges()->findOrFail($congeId);

        // Vérifier qu'il n'y a pas de chevauchement avec d'autres congés (sauf celui en cours)
        $dateDebut = $request->input('date_debut');
        $dateFin = $request->input('date_fin');

        $hasConflict = $personnel->conges()
            ->where('id', '!=', $congeId)
            ->where(function($query) use ($dateDebut, $dateFin) {
                $query->where(function($q) use ($dateDebut, $dateFin) {
                    $q->where('date_debut', '<=', $dateFin)
                      ->where('date_fin', '>=', $dateDebut);
                });
            })
            ->exists();

        if ($hasConflict) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['date_debut' => 'Cette période chevauche un congé déjà existant.']);
        }

        $conge->update([
            'date_debut' => $dateDebut,
            'date_fin' => $dateFin,
            'type' => $request->input('type'),
            'motif' => $request->input('motif'),
            'statut' => $request->input('statut', 'valide'),
        ]);

        return redirect()->back()
            ->with('success', 'Le congé a été mis à jour avec succès.');
    }

    /**
     * Supprime un congé
     */
    public function deleteConge(Personnel $personnel, $congeId)
    {
        $conge = $personnel->conges()->findOrFail($congeId);
        $conge->delete();

        return redirect()->back()
            ->with('success', 'Le congé a été supprimé avec succès.');
    }
}
