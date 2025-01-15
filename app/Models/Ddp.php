<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Ddp extends Model
{
    /** @use HasFactory<\Database\Factories\DdpFactory> */
    use HasFactory;

    protected $fillable = ['code', 'nom', 'ddp_cde_statut_id', 'user_id', 'dossier_suivi_par_id', 'afficher_destinataire'];

    public function statut(): BelongsTo {
        return $this->belongsTo(DdpCdeStatut::class, 'ddp_cde_statut_id');
    }
    public function ddpCdeStatut(): BelongsTo {
        return $this->belongsTo(DdpCdeStatut::class, 'ddp_cde_statut_id');
    }
    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }
    public function ddpLigne(): HasMany {
        return $this->hasMany(DdpLigne::class);
    }
    public function ddpLigneFournisseur(): HasManyThrough {
        return $this->hasManyThrough(DdpLigneFournisseur::class, DdpLigne::class);
    }
}
