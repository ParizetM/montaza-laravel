<?php

namespace App\Http\Controllers;

use App\Models\Affaire;
use App\Models\Materiel;
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
        $affaire->load(['materiels', 'reparations', 'ddps', 'cdes']);

        // Calculs pour les KPIs (similaires à ProductionController)
        $totalMateriels = $affaire->materiels->count();
        $totalReparations = $affaire->reparations->count();
        $totalDdp = $affaire->ddps->count();
        $totalCde = $affaire->cdes->count();

        $availableMateriels = Materiel::where('status', 'actif')->get();
        $statuts = Affaire::getStatuts();

        return view('affaires.show', compact('affaire', 'totalMateriels', 'totalReparations', 'totalDdp', 'totalCde', 'availableMateriels', 'statuts'));
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

        $request->validate([
            'materiel_id' => 'required|exists:materiels,id',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date|after_or_equal:date_debut',
        ]);

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
}
