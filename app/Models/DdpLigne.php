<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DdpLigne extends Model
{
    /** @use HasFactory<\Database\Factories\DdpLigneFactory> */
    use HasFactory;

    protected $fillable = ['ddp_id', 'matiere_id', 'quantite'];

    public function ddp(): BelongsTo {
        return $this->belongsTo(Ddp::class);
    }
    public function matiere(): BelongsTo {
        return $this->belongsTo(Matiere::class);
    }
    public function ddpLigneFournisseur(): HasMany {
        return $this->hasMany(DdpLigneFournisseur::class);
    }

}
