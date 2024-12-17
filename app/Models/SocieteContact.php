<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocieteContact extends Model
{
    /** @use HasFactory<\Database\Factories\SocieteContactFactory> */
    use HasFactory;

    protected $fillable = [
        'etablissement_id',
        'nom',
        'email',
        'telephone_portable',
        'telephone_fixe',
        'fonction',
    ];
    public function etablissement(): BelongsTo
    {
        return $this->belongsTo(Etablissement::class);
    }
}
