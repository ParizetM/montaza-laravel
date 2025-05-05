<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Cde extends Model
{
    /** @use HasFactory<\Database\Factories\CdeFactory> */
    use HasFactory;


    protected $fillable = [
        'code',
        'nom',
        'ddp_cde_statut_id',
        'user_id',
        'entite_id',
        'ddp_id',
        'affaire_numero',
        'affaire_nom',
        'devis_numero',
        'affaire_suivi_par_id',
        'acheteur_id',
        'tva',
        'type_expedition_id',
        'adresse_livraison',
        'adresse_facturation',
        'condition_paiement_id',
        'afficher_destinataire',
        'accuse_reception',
        'commentaire_id',
        'cde_note_id',
        'changement_livraison',
        'show_ref_fournisseur',
        'IS_STOCKE',
    ];
    public function cdeLignes()
    {
        return $this->hasMany(CdeLigne::class)->orderBy('poste');
    }
    public function ddpCdeStatut(): BelongsTo
    {
        return $this->belongsTo(DdpCdeStatut::class, 'ddp_cde_statut_id');
    }
    public function statut(): BelongsTo
    {
        return $this->belongsTo(DdpCdeStatut::class, 'ddp_cde_statut_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function entite(): BelongsTo
    {
        return $this->belongsTo(Entite::class);
    }
    public function ddp(): BelongsTo
    {
        return $this->belongsTo(Ddp::class);
    }
    public function societeContacts(): HasManyThrough
    {
        return $this->hasManyThrough(
            SocieteContact::class,
            CdeSocieteContact::class,
            'cde_id',            // Foreign key on CdeSocieteContact that references Cde
            'id',                // Foreign key on SocieteContact that references SocieteContact
            'id',                // Local key on Cde
            'societe_contact_id' // Local key on CdeSocieteContact
        );
    }
    public function hasSocieteContact(): bool
    {
        return $this->societeContacts()->exists();
    }
    public function etablissement()
    {
        // Get the first societe contact's etablissement
        $societeContact = $this->societeContacts()->first();
        return $societeContact ? $societeContact->etablissement() : null;
    }

    public function societe()
    {
        // Get the first societe contact's societe
        $societeContact = $this->societeContacts()->first();
        return $societeContact ? $societeContact->societe() : null;
    }
    public function affaireSuiviPar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'affaire_suivi_par_id');
    }
    public function acheteur(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function typeExpedition(): BelongsTo
    {
        return $this->belongsTo(TypeExpedition::class);
    }
    public function conditionPaiement(): BelongsTo
    {
        return $this->belongsTo(ConditionPaiement::class);
    }
    public function commentaire(): BelongsTo
    {
        return $this->belongsTo(Commentaire::class);
    }
    public function cdeNotes()
    {
        return $this->belongsToMany(CdeNote::class, 'cde_cde_notes', 'cde_id', 'cde_note_id');
    }

    public function mouvementsStock(): HasManyThrough
    {
        return $this->hasManyThrough(
            MouvementStock::class,
            CdeLigne::class,
            'cde_id',        // Foreign key on CdeLigne that references Cde
            'cde_ligne_id',  // Foreign key on MouvementStock that references CdeLigne
            'id',            // Local key on Cde
            'id'             // Local key on CdeLigne
        );
    }
}
