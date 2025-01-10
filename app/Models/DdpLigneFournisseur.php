<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use App\Models\DdpLigne;

class DdpLigneFournisseur extends Model
{
    /** @use HasFactory<\Database\Factories\DdpLigneFournisseurFactory> */
    use HasFactory;

    protected $fillable = ['ddp_ligne_id', 'societe_id', 'ddp_cde_statut_id'];

    public function ddpLigne(): BelongsTo {
        return $this->belongsTo(DdpLigne::class);
    }

    public function fournisseur(): BelongsTo {
        return $this->belongsTo(societe::class);
    }
    public function societe(): BelongsTo {
        return $this->belongsTo(societe::class);
    }
    public function ddp(): HasOneThrough {
        return $this->hasOneThrough(Ddp::class, DdpLigne::class, 'id', 'id', 'ddp_ligne_id', 'ddp_id');
    }
}
