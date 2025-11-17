<?php

namespace App\Http\Controllers;
use Illuminate\View\View;

use Illuminate\Http\Request;
use App\Models\Materiel;
use App\Models\Reparation;
use Illuminate\Support\Facades\Auth;

class ReparationController extends Controller
{
    public function index()
    {
        // Charger les réparations actives (non-archivées)
        $activeReparations = Reparation::with(['materiel', 'user'])
            ->whereNotIn('status', ['archived', 'closed'])
            ->latest()
            ->get();

        // Charger les réparations archivées
        $archivedReparations = Reparation::with(['materiel', 'user'])
            ->whereIn('status', ['archived', 'closed'])
            ->latest()
            ->get();

        return view('reparation.index', compact('activeReparations', 'archivedReparations'));
    }


    public function create()
    {
        $materiels = Materiel::where('desactive', false)->get();
        return view('reparation.create', compact('materiels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'materiel_id' => 'required|exists:materiels,id',
            'description' => 'required|string',
        ]);

        // Créer la demande de réparation
        $reparation = Reparation::create([
            'user_id' => Auth::id(),
            'materiel_id' => $request->input('materiel_id'),
            'description' => $request->input('description'),
            'status' => 'pending',
        ]);

        // Mettre le matériel en inactif
        $materiel = Materiel::find($request->input('materiel_id'));
        if ($materiel) {
            $materiel->status = 'inactif';
            $materiel->save();
        }

        return redirect()->route('reparation.index')->with('success', 'Demande de réparation créée avec succès.');
    }

    /**
     * Affiche le détail d'une réparation
     */
    public function show(Reparation $reparation)
    {
        $reparation->load(['materiel', 'user']);
        return view('reparation.show', compact('reparation'));
    }

    /**
     * Affiche le formulaire d'édition pour une réparation
     */
    public function edit(Reparation $reparation)
    {
        // Bloquer la modification des réparations archivées
        if ($reparation->status === 'archived' || $reparation->status === 'closed') {
            abort(403, 'Les réparations archivées ne peuvent pas être modifiées.');
        }

        // Autorisation: le demandeur peut modifier sa demande, ou un utilisateur ayant la permission
        if (Auth::id() !== $reparation->user_id && !Auth::user()->hasPermission('gerer_les_reparations')) {
            abort(403);
        }

        return view('reparation.edit', compact('reparation'));
    }

    /**
     * Met à jour la réparation
     */
    public function update(Request $request, Reparation $reparation)
    {
        // Bloquer la modification des réparations archivées
        if ($reparation->status === 'archived' || $reparation->status === 'closed') {
            abort(403, 'Les réparations archivées ne peuvent pas être modifiées.');
        }

        if (Auth::id() !== $reparation->user_id && !Auth::user()->hasPermission('gerer_les_reparations')) {
            abort(403);
        }

        $data = $request->validate([
            'description' => 'required|string',
            'status' => 'required|in:pending,in_progress,completed,closed',
        ]);

        $reparation->update($data);

        return redirect()->route('reparation.show', $reparation->id)->with('success', 'Réparation mise à jour.');
    }

    /**
     * Met à jour uniquement le statut d'une réparation (depuis la page show)
     */
    public function updateStatus(Request $request, Reparation $reparation)
    {
        // Bloquer le changement de statut des réparations archivées
        if ($reparation->status === 'archived' || $reparation->status === 'closed') {
            abort(403, 'Les réparations archivées ne peuvent pas être modifiées.');
        }

        if (Auth::id() !== $reparation->user_id && !Auth::user()->hasPermission('gerer_les_reparations')) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:pending,in_progress,completed,closed',
        ]);

        $newStatus = $request->input('status');

        // Si "completed", archiver automatiquement
        if ($newStatus === 'completed') {
            $reparation->status = 'archived';
            $reparation->save();
            $this->reconcileMaterielStatus($reparation->materiel_id);
            return redirect()->route('reparation.show', $reparation->id)->with('success', 'Réparation archivée automatiquement.');
        }

        $reparation->status = $newStatus;
        $reparation->save();

        return redirect()->route('reparation.show', $reparation->id)->with('success', 'Statut mis à jour.');
    }

    /**
     * Archive manuellement une réparation
     */
    public function archive(Request $request, Reparation $reparation)
    {
        if (Auth::id() !== $reparation->user_id && !Auth::user()->hasPermission('gerer_les_reparations')) {
            abort(403);
        }

        $reparation->status = 'archived';
        $reparation->save();

        $this->reconcileMaterielStatus($reparation->materiel_id);

        return redirect()->route('reparation.show', $reparation->id)->with('success', 'Réparation archivée.');
    }

    /**
     * Vérifie les réparations d'un matériel :
     * - si des réparations actives existent => matériel reste inactif
     * - sinon matériel redevient actif
     */
    protected function reconcileMaterielStatus($materielId)
    {
        $materiel = Materiel::find($materielId);
        if (!$materiel) {
            return;
        }

        $hasOpen = Reparation::where('materiel_id', $materielId)
            ->whereNotIn('status', ['archived', 'closed'])
            ->exists();

        $materiel->status = $hasOpen ? 'inactif' : 'actif';
        $materiel->save();
    }
}
