<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\FormeJuridique;
use App\Models\CodeApe;
use App\Models\SocieteType;
use App\Models\Etablissement;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Societe extends Model
{
    /** @use HasFactory<SocieteFactory> */
    use HasFactory;
    protected $fillable = [
        'raison_sociale',
        'siren',
        'forme_juridique_id',
        'code_ape_id',
        'societe_type_id',
        'site_web'
    ];
    public function formeJuridique(): BelongsTo
    {
        return $this->belongsTo(FormeJuridique::class);
    }
    public function codeApe(): BelongsTo
    {
        return $this->belongsTo(CodeApe::class);
    }
    public function societeType(): BelongsTo
    {
        return $this->belongsTo(SocieteType::class, 'societe_type_id');
    }
    public function etablissements(): HasMany
    {
        return $this->hasMany(Etablissement::class);
    }
    public function commentaire(): BelongsTo
    {
        return $this->belongsTo(Commentaire::class);
    }
    public function hasCommentaire(): bool
    {
        return $this->commentaire()->exists();
    }
}
