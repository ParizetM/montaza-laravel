<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'societe_contact_id',
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
    ];
    public function cdeLignes()
    {
        return $this->hasMany(CdeLigne::class);
    }
    public function ddpCdeStatut(): BelongsTo
    {
        return $this->belongsTo(DdpCdeStatut::class, 'ddp_cde_statut_id');
    }
    public function statut(): BelongsTo {
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
    public function societeContact(): BelongsTo
    {
        return $this->belongsTo(SocieteContact::class);
    }

    public function etablissement()
    {
        return $this->hasOneThrough(Etablissement::class, SocieteContact::class, 'id', 'id', 'societe_contact_id', 'etablissement_id');
    }
    public function societe()
    {
        return $this->societeContact->etablissement->societe();
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



}
