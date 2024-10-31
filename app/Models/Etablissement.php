<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\SocieteContact;


class Etablissement extends Model
{
    /** @use HasFactory<EtablissementFactory> */
    use HasFactory;
    protected $fillable = [
        'adresse',
        'nom',
        'code_postal',
        'ville',
        'region',
        'pay_id',
        'societe_id',
        'siret'
    ];
    public function pays(): BelongsTo
    {
        return $this->belongsTo(Pays::class, 'pay_id');
    }
    public function societeContacts(): HasMany
    {
        return $this->hasMany(SocieteContact::class);
    }

}
