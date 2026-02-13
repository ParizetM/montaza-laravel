<?php

namespace App\Http\Controllers;

use App\Models\Affaire;
use App\Models\Materiel;
use App\Models\Personnel;
use App\Models\AffairePersonnel;
use App\Models\AffairePersonnelTache;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;

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
            'statut' => 'nullable|string',
        ]);

        $nombre = intval($request->input('nombre', 50));
        $search = $request->input('search');
        $statut = $request->input('statut');

        // Construction de la requête avec filtres
        $query = Affaire::query();

        // Appliquer le filtre par statut
        if ($statut) {
            $query->where('statut', $statut);
        }

        // Appliquer la recherche
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'LIKE', "%{$search}%")
                    ->orWhere('nom', 'LIKE', "%{$search}%");
            });
        }

        // Récupérer les affaires paginées
        $affaires = $query->orderByRaw("CASE
            WHEN statut = 'en_attente' THEN 1
            WHEN statut = 'en_cours' THEN 2
            WHEN statut = 'termine' THEN 3
            WHEN statut = 'archive' THEN 4
            ELSE 5 END ASC")
            ->orderBy('created_at', 'desc')
            ->paginate($nombre);

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
            'date_debut' => 'required|date',
            'date_fin_prevue' => 'required|date|after_or_equal:date_debut',
        ]);

        $affaire = Affaire::create([
            'code' => $request->input('code'),
            'nom' => $request->input('nom'),
            'budget' => $request->input('budget'),
            'date_debut' => $request->input('date_debut'),
            'date_fin_prevue' => $request->input('date_fin_prevue'),
            'statut' => 'en_cours', // Statut par défaut
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

        // Chargement des relations nécessaires pour la vue détaillée (fusionnée avec production)
        $affaire->load(['materiels', 'reparations', 'ddps', 'cdes', 'devisTuyauteries', 'personnels']);

        // Calculs pour les KPIs (similaires à ProductionController)
        $totalMateriels = $affaire->materiels->count();
        $totalReparations = $affaire->reparations->count();
        $totalDdp = $affaire->ddps->count();
        $totalCde = $affaire->cdes->count();

        $availableMateriels = Materiel::where('status', 'actif')->get();
        $statuts = Affaire::getStatuts();

        // Récupérer les devis tuyauterie non assignés
        $devisNonAssignes = \App\Models\DevisTuyauterie::whereNull('affaire_id')
            ->where('is_archived', false)
            ->orderBy('date_emission', 'desc')
            ->get();

        return view('affaires.show', compact('affaire', 'totalMateriels', 'totalReparations', 'totalDdp', 'totalCde', 'availableMateriels', 'statuts', 'devisNonAssignes'));
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
        if ($affaire->statut === Affaire::STATUT_TERMINE || $affaire->statut === Affaire::STATUT_ARCHIVE) {
            return redirect()->back()->with('error', 'Cette affaire est terminée ou archivée et ne peut plus être modifiée.');
        }

        $request->validate([
            'code' => 'required|string|max:255|unique:affaires,code,' . $affaire->id,
            'nom' => 'required|string|max:255',
            'budget' => 'nullable|numeric',
            'date_debut' => 'required|date',
        ]);

        $affaire->update([
            'code' => $request->input('code'),
            'nom' => $request->input('nom'),
            'budget' => $request->input('budget'),
            'date_debut' => $request->input('date_debut'),
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
    public function actualiserAllTotals()
    {
        $affaires = Affaire::all();
        foreach ($affaires as $affaire) {
            $affaire->updateTotal();
        }

        return redirect()->route('affaires.index')
            ->with('success', 'Tous les totaux des affaires ont été actualisés avec succès.');
    }

    // --- Méthodes ajoutées depuis ProductionController ---

    /**
     * Met à jour le statut de l'affaire (Production).
     */
    public function updateStatus(Request $request, Affaire $affaire)
    {
        if ($affaire->statut === Affaire::STATUT_TERMINE || $affaire->statut === Affaire::STATUT_ARCHIVE) {
            return redirect()->back()->with('error', 'Cette affaire est terminée ou archivée et ne peut plus être modifiée.');
        }

        $request->validate([
            'statut' => 'required|in:' . implode(',', array_keys(Affaire::getStatuts())),
        ]);

        if ($affaire->statut === Affaire::STATUT_EN_ATTENTE && $request->statut === Affaire::STATUT_TERMINE) {
            return redirect()->back()->with('error', 'Impossible de passer directement de "En attente" à "Terminé".');
        }

        $affaire->update(['statut' => $request->statut]);

        return redirect()->back()->with('success', 'Statut mis à jour avec succès.');
    }

    /**
     * Assigne un matériel à l'affaire.
     */
    public function assignMateriel(Request $request, Affaire $affaire)
    {
        if ($affaire->statut === Affaire::STATUT_TERMINE || $affaire->statut === Affaire::STATUT_ARCHIVE) {
            return redirect()->back()->with('error', 'Cette affaire est terminée ou archivée et ne peut plus être modifiée.');
        }

        $rules = [
            'materiel_id' => 'required|exists:materiels,id',
            'date_debut' => 'required|date',
            'date_fin' => ['nullable', 'date', 'after_or_equal:date_debut'],
        ];

        // Ajouter la validation de la date d'échéance si elle existe
        if ($affaire->date_fin_prevue) {
            $rules['date_fin'][] = 'before_or_equal:' . $affaire->date_fin_prevue;
        }

        $messages = [
            'date_fin.before_or_equal' => 'La date de fin de l\'assignation ne peut pas dépasser la date d\'échéance de l\'affaire (' . ($affaire->date_fin_prevue ? Carbon::parse($affaire->date_fin_prevue)->format('d/m/Y') : '') . ').',
        ];

        $request->validate($rules, $messages);

        $affaire->materiels()->attach($request->materiel_id, [
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'statut' => 'reserve',
        ]);

        return redirect()->back()->with('success', 'Matériel assigné avec succès.');
    }

    /**
     * Détache un matériel de l'affaire.
     */
    public function detachMateriel(Affaire $affaire, Materiel $materiel)
    {
        if ($affaire->statut === Affaire::STATUT_TERMINE || $affaire->statut === Affaire::STATUT_ARCHIVE) {
            return redirect()->back()->with('error', 'Cette affaire est terminée ou archivée et ne peut plus être modifiée.');
        }

        // Mettre à jour la date de fin à maintenant au lieu de détacher complètement
        // pour garder l'historique
        $affaire->materiels()->updateExistingPivot($materiel->id, [
            'date_fin' => now(),
            'statut' => 'termine'
        ]);

        return redirect()->back()->with('success', 'Matériel retiré de l\'affaire.');
    }

    /**
     * Affiche la colonne des affaires pour le tableau de bord.
     */
    public function indexColAffaire()
    {
        $affaires = Affaire::orderByRaw("CASE
            WHEN statut = 'en_cours' THEN 1
            WHEN statut = 'en_attente' THEN 2
            WHEN statut = 'termine' THEN 3
            WHEN statut = 'archive' THEN 4
            ELSE 5 END ASC")
            ->orderBy('updated_at', 'desc')
            ->take(30)
            ->get();
        return view('affaires.index_col', compact('affaires'));
    }

    /**
     * Affiche la version réduite de la colonne des affaires pour le tableau de bord.
     */
    public function indexColAffaireSmall()
    {
        $affaires = Affaire::orderByRaw("CASE
            WHEN statut = 'en_cours' THEN 1
            WHEN statut = 'en_attente' THEN 2
            WHEN statut = 'termine' THEN 3
            WHEN statut = 'archive' THEN 4
            ELSE 5 END ASC")
            ->orderBy('updated_at', 'desc')
            ->take(5)
            ->get();
        return view('affaires.index_col', compact('affaires'));
    }

    /**
     * Affiche le planning global des affaires.
     */
    public function planning(Request $request)
    {
        $start = $request->input('start') ? \Carbon\Carbon::parse($request->input('start')) : now()->startOfMonth()->subMonth();
        $end = $request->input('end') ? \Carbon\Carbon::parse($request->input('end')) : now()->endOfMonth()->addMonths(2);

        // Récupérer les affaires qui chevauchent la période
        $affaires = Affaire::where(function ($query) use ($start, $end) {
            $query->whereNotNull('date_debut')
                  ->where('date_debut', '<=', $end)
                  ->where(function ($q) use ($start) {
                      $q->where('date_fin_prevue', '>=', $start)
                        ->orWhereNull('date_fin_prevue'); // Inclure si pas de date de fin (cas rare mais possible)
                  });
        })->get();

        // Préparer les données pour la vue
        $planningData = $affaires->map(function ($affaire) use ($start, $end) {
            $dateDebut = \Carbon\Carbon::parse($affaire->date_debut);
            $dateFinPrevue = $affaire->date_fin_prevue ? \Carbon\Carbon::parse($affaire->date_fin_prevue) : $dateDebut;

            // Logique de prolongation automatique
            $isFinished = in_array($affaire->statut, [Affaire::STATUT_TERMINE, Affaire::STATUT_ARCHIVE]);
            $today = now()->startOfDay();

            // Date de fin effective pour l'affichage
            if ($isFinished) {
                // Si terminé, on utilise la date de fin réelle si elle existe, sinon la prévue
                $dateFinEffective = $affaire->date_fin_reelle ? \Carbon\Carbon::parse($affaire->date_fin_reelle) : $dateFinPrevue;
            } else {
                // Si non terminé et date prévue passée, on prolonge jusqu'à aujourd'hui
                if ($dateFinPrevue->lt($today)) {
                    $dateFinEffective = $today;
                } else {
                    $dateFinEffective = $dateFinPrevue;
                }
            }

            // Calcul des positions pour le Gantt
            // On borne les dates par la période affichée pour le calcul CSS
            $displayStart = $dateDebut->lt($start) ? $start : $dateDebut;
            $displayEnd = $dateFinEffective->gt($end) ? $end : $dateFinEffective;

            $totalDays = $start->diffInDays($end) + 1; // +1 pour inclure le dernier jour
            $offsetDays = $start->diffInDays($displayStart);
            $durationDays = $displayStart->diffInDays($displayEnd) + 1;

            $leftPercent = ($offsetDays / $totalDays) * 100;
            $widthPercent = ($durationDays / $totalDays) * 100;

            // Détection du retard pour le style
            $isDelayed = !$isFinished && $dateFinPrevue->lt($today);

            // Calcul de la partie "retard" si nécessaire (pour hachurage)
            $delayedWidthPercent = 0;
            if ($isDelayed && $dateFinPrevue->gt($displayStart)) {
                 // La partie normale s'arrête à dateFinPrevue
                 // La partie retard commence à dateFinPrevue et finit à displayEnd (qui est today ou borné par la vue)
                 $normalEnd = $dateFinPrevue->gt($end) ? $end : $dateFinPrevue;
                 $normalDuration = $displayStart->diffInDays($normalEnd) + 1;
                 $normalWidthPercent = ($normalDuration / $totalDays) * 100;

                 // Ajustement visuel : la barre principale sera la partie normale, on ajoutera un pseudo-element ou une div enfant pour le retard
                 // Simplification : On garde widthPercent total, et on passera un ratio de retard
            }

            return (object) [
                'affaire' => $affaire,
                'left' => $leftPercent,
                'width' => $widthPercent,
                'is_delayed' => $isDelayed,
                'date_debut' => $dateDebut,
                'date_fin_effective' => $dateFinEffective,
                'date_fin_prevue' => $dateFinPrevue
            ];
        });

        return view('affaires.planning', compact('planningData', 'start', 'end'));
    }

    public function assignDevis(Request $request, Affaire $affaire)
    {
        $request->validate([
            'devis_ids' => 'required|array|min:1',
            'devis_ids.*' => 'exists:devis_tuyauteries,id'
        ], [
            'devis_ids.required' => 'Veuillez sélectionner au moins un devis.',
            'devis_ids.min' => 'Veuillez sélectionner au moins un devis.'
        ]);

        $devisIds = $request->input('devis_ids');

        // Assigner les devis à l'affaire
        \App\Models\DevisTuyauterie::whereIn('id', $devisIds)
            ->whereNull('affaire_id')
            ->update(['affaire_id' => $affaire->id]);

        $count = count($devisIds);
        $message = $count > 1
            ? "{$count} devis ont été assignés à l'affaire avec succès."
            : "Le devis a été assigné à l'affaire avec succès.";

        return redirect()->route('affaires.show', $affaire)
            ->with('success', $message);
    }

    public function unassignDevis(Affaire $affaire, $devisId)
    {
        $devis = \App\Models\DevisTuyauterie::findOrFail($devisId);

        // Vérifier que le devis appartient bien à cette affaire
        if ($devis->affaire_id !== $affaire->id) {
            return redirect()->route('affaires.show', $affaire)
                ->with('error', 'Ce devis n\'est pas assigné à cette affaire.');
        }

        // Désassigner le devis
        $devis->update(['affaire_id' => null]);

        return redirect()->route('affaires.show', $affaire)
            ->with('success', 'Le devis a été désassigné de l\'affaire avec succès.');
    }

    /**
     * Assigne du personnel à une affaire
     */
    public function assignPersonnel(Request $request, Affaire $affaire)
    {
        $rules = [
            'personnel_ids' => 'required|array|min:1',
            'personnel_ids.*' => 'exists:personnels,id',
            'role' => 'nullable|string|max:255',
            'date_debut' => 'nullable|date',
            'date_fin' => ['nullable', 'date', 'after_or_equal:date_debut'],
            'notes' => 'nullable|string',
        ];

        // Ajouter la validation de la date d'échéance si elle existe
        if ($affaire->date_fin_prevue) {
            $rules['date_fin'][] = 'before_or_equal:' . $affaire->date_fin_prevue;
        }

        $messages = [
            'personnel_ids.required' => 'Veuillez sélectionner au moins un employé.',
            'personnel_ids.min' => 'Veuillez sélectionner au moins un employé.',
            'date_fin.before_or_equal' => 'La date de fin de l\'assignation ne peut pas dépasser la date d\'échéance de l\'affaire (' . ($affaire->date_fin_prevue ? Carbon::parse($affaire->date_fin_prevue)->format('d/m/Y') : '') . ').',
        ];

        $request->validate($rules, $messages);

        $personnelIds = $request->input('personnel_ids');
        $pivotData = [];

        // Préparer les données pivot pour chaque personnel
        foreach ($personnelIds as $personnelId) {
            if (!$affaire->personnels()->where('personnel_id', $personnelId)->exists()) {
                $pivotData[$personnelId] = [
                    'role' => $request->input('role'),
                    'date_debut' => $request->input('date_debut'),
                    'date_fin' => $request->input('date_fin'),
                    'notes' => $request->input('notes'),
                ];
            }
        }

        // Attacher le personnel avec les données pivot
        $affaire->personnels()->attach($pivotData);

        $count = count($pivotData);
        $message = $count > 1
            ? "{$count} employés ont été assignés à l'affaire avec succès."
            : "L'employé a été assigné à l'affaire avec succès.";

        return redirect()->route('affaires.show', $affaire)
            ->with('success', $message);
    }

    /**
     * Désassigne un employé d'une affaire
     */
    public function unassignPersonnel(Affaire $affaire, $personnelId)
    {
        $personnel = Personnel::findOrFail($personnelId);

        // Vérifier que le personnel est assigné à cette affaire
        if (!$affaire->personnels()->where('personnel_id', $personnelId)->exists()) {
            return redirect()->route('affaires.show', $affaire)
                ->with('error', 'Cet employé n\'est pas assigné à cette affaire.');
        }

        // Désassigner le personnel
        $affaire->personnels()->detach($personnelId);

        return redirect()->route('affaires.show', $affaire)
            ->with('success', 'L\'employé a été désassigné de l\'affaire avec succès.');
    }

    /**
     * Met à jour les informations d'assignation d'un employé à une affaire
     */
    public function updatePersonnelAssignment(Request $request, Affaire $affaire, $personnelId)
    {
        $rules = [
            'role' => 'nullable|string|max:255',
            'date_debut' => 'nullable|date',
            'date_fin' => ['nullable', 'date', 'after_or_equal:date_debut'],
            'notes' => 'nullable|string',
        ];

        // Ajouter la validation de la date d'échéance si elle existe
        if ($affaire->date_fin_prevue) {
            $rules['date_fin'][] = 'before_or_equal:' . $affaire->date_fin_prevue;
        }

        $messages = [
            'date_fin.before_or_equal' => 'La date de fin de l\'assignation ne peut pas dépasser la date d\'échéance de l\'affaire (' . ($affaire->date_fin_prevue ? Carbon::parse($affaire->date_fin_prevue)->format('d/m/Y') : '') . ').',
        ];

        $request->validate($rules, $messages);

        // Vérifier que le personnel est assigné
        if (!$affaire->personnels()->where('personnel_id', $personnelId)->exists()) {
            return redirect()->route('affaires.show', $affaire)
                ->with('error', 'Cet employé n\'est pas assigné à cette affaire.');
        }

        // Mettre à jour les données pivot
        $affaire->personnels()->updateExistingPivot($personnelId, [
            'role' => $request->input('role'),
            'date_debut' => $request->input('date_debut'),
            'date_fin' => $request->input('date_fin'),
            'notes' => $request->input('notes'),
        ]);

        return redirect()->route('affaires.show', $affaire)
            ->with('success', 'Les informations de l\'employé ont été mises à jour avec succès.');
    }

    /**
     * Affiche les tâches d'un personnel assigné à une affaire
     */
    public function showPersonnelTaches(Affaire $affaire, $personnelId)
    {
        $personnel = Personnel::findOrFail($personnelId);

        // Vérifier que le personnel est assigné
        $pivotRecord = AffairePersonnel::where('affaire_id', $affaire->id)
            ->where('personnel_id', $personnelId)
            ->firstOrFail();

        // Mettre à jour automatiquement les statuts des tâches
        $this->updateTachesStatuts($pivotRecord);

        $taches = $pivotRecord->taches()->orderBy('ordre')->orderBy('date_debut')->get();

        return view('affaires.personnel-taches', compact('affaire', 'personnel', 'pivotRecord', 'taches'));
    }

    /**
     * Met à jour automatiquement le statut des tâches en fonction de la date
     */
    private function updateTachesStatuts(AffairePersonnel $pivotRecord)
    {
        $today = now()->startOfDay();

        // Récupérer toutes les tâches qui ne sont pas terminées
        $taches = $pivotRecord->taches()->whereIn('statut', ['a_faire', 'en_cours'])->get();

        foreach ($taches as $tache) {
            $dateDebut = $tache->date_debut->startOfDay();

            // Si la date de début est atteinte ou dépassée et que la tâche est "à faire", la passer en "en cours"
            if ($dateDebut->lte($today) && $tache->statut === 'a_faire') {
                $tache->update(['statut' => 'en_cours']);
            }
        }
    }

    /**
     * Marque une tâche comme terminée
     */
    public function completePersonnelTache(Affaire $affaire, $personnelId, $tacheId)
    {
        $tache = AffairePersonnelTache::findOrFail($tacheId);

        // Vérifier que la tâche appartient bien à ce pivot
        $pivotRecord = AffairePersonnel::where('affaire_id', $affaire->id)
            ->where('personnel_id', $personnelId)
            ->firstOrFail();

        if ($tache->affaire_personnel_id !== $pivotRecord->id) {
            return redirect()->back()
                ->with('error', 'Cette tâche n\'appartient pas à cet employé.');
        }

        // Mettre à jour le statut
        $tache->update(['statut' => 'termine']);

        return redirect()->back()
            ->with('success', 'La tâche a été marquée comme terminée.');
    }

    /**
     * Réouvre une tâche terminée
     */
    public function reopenPersonnelTache(Affaire $affaire, $personnelId, $tacheId)
    {
        $tache = AffairePersonnelTache::findOrFail($tacheId);

        // Vérifier que la tâche appartient bien à ce pivot
        $pivotRecord = AffairePersonnel::where('affaire_id', $affaire->id)
            ->where('personnel_id', $personnelId)
            ->firstOrFail();

        if ($tache->affaire_personnel_id !== $pivotRecord->id) {
            return redirect()->back()
                ->with('error', 'Cette tâche n\'appartient pas à cet employé.');
        }

        // Mettre à jour le statut
        $tache->update(['statut' => 'en_cours']);

        return redirect()->back()
            ->with('success', 'La tâche a été réouverte.');
    }

    /**
     * Ajoute une tâche à un personnel assigné
     */
    public function storePersonnelTache(Request $request, Affaire $affaire, $personnelId)
    {
        // Préparer les règles de validation
        $rules = [
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_debut' => 'required|date',
            'creneau_debut' => 'required|in:matin,apres_midi',
            'date_fin' => [
                'required',
                'date',
                'after_or_equal:date_debut',
            ],
            'creneau_fin' => 'required|in:matin,apres_midi',
            'statut' => 'nullable|in:a_faire,en_cours,termine',
            'priorite' => 'nullable|in:basse,normale,haute',
        ];

        // Ajouter la validation de la date d'échéance si elle existe
        if ($affaire->date_fin_prevue) {
            $rules['date_fin'][] = 'before_or_equal:' . $affaire->date_fin_prevue;
        }

        $messages = [
            'date_fin.before_or_equal' => 'La date de fin de la tâche ne peut pas dépasser la date d\'échéance de l\'affaire (' . ($affaire->date_fin_prevue ? Carbon::parse($affaire->date_fin_prevue)->format('d/m/Y') : '') . ').',
        ];

        $request->validate($rules, $messages);

        // Vérifier que le personnel est assigné
        $pivotRecord = AffairePersonnel::where('affaire_id', $affaire->id)
            ->where('personnel_id', $personnelId)
            ->firstOrFail();

        // Vérifier les conflits de chevauchement
        $dateDebut = $request->input('date_debut');
        $creneauDebut = $request->input('creneau_debut');
        $dateFin = $request->input('date_fin');
        $creneauFin = $request->input('creneau_fin');

        // Valider que créneau_début <= créneau_fin si même date
        if ($dateDebut === $dateFin && $creneauDebut === 'apres_midi' && $creneauFin === 'matin') {
            return redirect()->back()
                ->withInput()
                ->withErrors(['creneau_fin' => 'Le créneau de fin doit être après le créneau de début pour une même journée.']);
        }

        if ($this->hasTaskConflict($personnelId, $dateDebut, $creneauDebut, $dateFin, $creneauFin)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['date_debut' => 'Cette période chevauche une tâche déjà existante pour cet employé.']);
        }

        // Vérifier que l'employé n'est pas en congé sur cette période
        if ($this->hasCongeConflict($personnelId, $dateDebut, $dateFin)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['date_debut' => 'L\'employé est en congé sur cette période.']);
        }

        // Vérifier que la date de fin de la tâche ne dépasse pas la date d'échéance de l'affaire
        if ($affaire->date_fin_prevue && Carbon::parse($dateFin)->gt(Carbon::parse($affaire->date_fin_prevue))) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['date_fin' => 'La date de fin de la tâche ne peut pas dépasser la date d\'échéance de l\'affaire (' . Carbon::parse($affaire->date_fin_prevue)->format('d/m/Y') . ').']);
        }

        AffairePersonnelTache::create([
            'affaire_personnel_id' => $pivotRecord->id,
            'titre' => $request->input('titre'),
            'description' => $request->input('description'),
            'date_debut' => $dateDebut,
            'creneau_debut' => $creneauDebut,
            'date_fin' => $dateFin,
            'creneau_fin' => $creneauFin,
            'statut' => $request->input('statut', 'a_faire'),
            'priorite' => $request->input('priorite', 'normale'),
        ]);

        return redirect()->back()
            ->with('success', 'La tâche a été ajoutée avec succès.');
    }

    /**
     * Met à jour une tâche
     */
    public function updatePersonnelTache(Request $request, Affaire $affaire, $personnelId, $tacheId)
    {
        // Préparer les règles de validation
        $rules = [
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_debut' => 'required|date',
            'creneau_debut' => 'required|in:matin,apres_midi',
            'date_fin' => [
                'required',
                'date',
                'after_or_equal:date_debut',
            ],
            'creneau_fin' => 'required|in:matin,apres_midi',
            'statut' => 'nullable|in:a_faire,en_cours,termine',
            'priorite' => 'nullable|in:basse,normale,haute',
        ];

        // Ajouter la validation de la date d'échéance si elle existe
        if ($affaire->date_fin_prevue) {
            $rules['date_fin'][] = 'before_or_equal:' . $affaire->date_fin_prevue;
        }

        $messages = [
            'date_fin.before_or_equal' => 'La date de fin de la tâche ne peut pas dépasser la date d\'échéance de l\'affaire (' . ($affaire->date_fin_prevue ? Carbon::parse($affaire->date_fin_prevue)->format('d/m/Y') : '') . ').',
        ];

        $request->validate($rules, $messages);

        $tache = AffairePersonnelTache::findOrFail($tacheId);

        // Vérifier les conflits de chevauchement (sauf avec la tâche actuelle)
        $dateDebut = $request->input('date_debut');
        $creneauDebut = $request->input('creneau_debut');
        $dateFin = $request->input('date_fin');
        $creneauFin = $request->input('creneau_fin');

        // Valider que créneau_début <= créneau_fin si même date
        if ($dateDebut === $dateFin && $creneauDebut === 'apres_midi' && $creneauFin === 'matin') {
            return redirect()->back()
                ->withInput()
                ->withErrors(['creneau_fin' => 'Le créneau de fin doit être après le créneau de début pour une même journée.']);
        }

        if ($this->hasTaskConflict($personnelId, $dateDebut, $creneauDebut, $dateFin, $creneauFin, $tacheId)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['date_debut' => 'Cette période chevauche une tâche déjà existante pour cet employé.']);
        }

        // Vérifier que l'employé n'est pas en congé sur cette période
        if ($this->hasCongeConflict($personnelId, $dateDebut, $dateFin)) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['date_debut' => 'L\'employé est en congé sur cette période.']);
        }

        // Vérifier que la date de fin de la tâche ne dépasse pas la date d'échéance de l'affaire
        if ($affaire->date_fin_prevue && Carbon::parse($dateFin)->gt(Carbon::parse($affaire->date_fin_prevue))) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['date_fin' => 'La date de fin de la tâche ne peut pas dépasser la date d\'échéance de l\'affaire (' . Carbon::parse($affaire->date_fin_prevue)->format('d/m/Y') . ').']);
        }

        $tache->update([
            'titre' => $request->input('titre'),
            'description' => $request->input('description'),
            'date_debut' => $dateDebut,
            'creneau_debut' => $creneauDebut,
            'date_fin' => $dateFin,
            'creneau_fin' => $creneauFin,
            'statut' => $request->input('statut'),
            'priorite' => $request->input('priorite'),
        ]);

        return redirect()->back()
            ->with('success', 'La tâche a été mise à jour avec succès.');
    }

    private function hasTaskConflict($personnelId, $dateDebut, $creneauDebut, $dateFin, $creneauFin, $excludeTacheId = null)
    {
        // Récupérer tous les enregistrements pivot pour ce personnel
        $affairePersonnels = AffairePersonnel::where('personnel_id', $personnelId)->get();

        foreach ($affairePersonnels as $affairePersonnel) {
            // Récupérer toutes les tâches de cette assignation
            $tachesQuery = $affairePersonnel->taches();

            // Exclure la tâche en cours de modification
            if ($excludeTacheId) {
                $tachesQuery->where('id', '!=', $excludeTacheId);
            }

            $taches = $tachesQuery->get();

            foreach ($taches as $tache) {
                // Vérifier le chevauchement en tenant compte des créneaux
                // Deux tâches se chevauchent si leurs périodes se superposent

                $tacheDebut = $tache->date_debut->format('Y-m-d') . '-' . ($tache->creneau_debut ?? 'matin');
                $tacheFin = $tache->date_fin->format('Y-m-d') . '-' . ($tache->creneau_fin ?? 'apres_midi');

                $nouvelleDebut = $dateDebut . '-' . $creneauDebut;
                $nouvelleFin = $dateFin . '-' . $creneauFin;

                // Comparer en tenant compte des demi-journées
                if ($this->comparePeriodes($nouvelleDebut, $nouvelleFin, $tacheDebut, $tacheFin)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Compare deux périodes pour détecter un chevauchement
     */
    private function comparePeriodes($debut1, $fin1, $debut2, $fin2)
    {
        // Convertir en timestamps comparables
        $d1 = $this->convertToTimestamp($debut1);
        $f1 = $this->convertToTimestamp($fin1);
        $d2 = $this->convertToTimestamp($debut2);
        $f2 = $this->convertToTimestamp($fin2);

        // Deux périodes se chevauchent si début1 <= fin2 ET fin1 >= début2
        return $d1 <= $f2 && $f1 >= $d2;
    }

    /**
     * Convertit une date-créneau en timestamp comparable
     */
    private function convertToTimestamp($dateCreneauString)
    {
        // Trouver la dernière occurrence du tiret pour séparer la date du créneau
        $lastDashPos = strrpos($dateCreneauString, '-');
        $date = substr($dateCreneauString, 0, $lastDashPos);
        $creneau = substr($dateCreneauString, $lastDashPos + 1);

        $timestamp = strtotime($date);

        // Ajouter 0.5 pour l'après-midi pour différencier matin et après-midi
        if ($creneau === 'apres_midi') {
            $timestamp += 0.5;
        }

        return $timestamp;
    }

    /**
     * Vérifie si l'employé a un congé sur la période donnée
     */
    private function hasCongeConflict($personnelId, $dateDebut, $dateFin)
    {
        $personnel = Personnel::findOrFail($personnelId);

        return $personnel->conges()
            ->where('statut', 'valide')
            ->where(function($query) use ($dateDebut, $dateFin) {
                $query->where(function($q) use ($dateDebut, $dateFin) {
                    $q->where('date_debut', '<=', $dateFin)
                      ->where('date_fin', '>=', $dateDebut);
                });
            })
            ->exists();
    }

    /**
     * Supprime une tâche d'un personnel
     */
    public function deletePersonnelTache(Affaire $affaire, $personnelId, $tacheId)
    {
        $tache = AffairePersonnelTache::findOrFail($tacheId);
        $tache->delete();

        return redirect()->back()
            ->with('success', 'La tâche a été supprimée avec succès.');
    }
}

