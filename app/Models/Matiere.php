<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Matiere extends Model
{
    use HasFactory;
    protected $fillable = [
        'ref_interne',
        'designation',
        'societe_id',
        'unite_id',
        'sous_famille_id',
        'dn',
        'epaisseur',
        'prix_moyen',
        'quantite',
        'stock_min',
    ];

    public function fournisseurs()
    {
        return $this->belongsToMany(Societe::class, 'societe_matiere')
            ->withPivot(['ref_fournisseur', 'designation_fournisseur', 'prix','unite_id', 'date_dernier_prix'])
            ->withTimestamps();
    }
    public function unite()
    {
        return $this->belongsTo(Unite::class);
    }
    public function sousFamille()
    {
        return $this->belongsTo(SousFamille::class);
    }
    public function societe()
    {
        return $this->belongsTo(Societe::class);
    }
    public function standardVersion()
    {
        return $this->belongsTo(StandardVersion::class);
    }
    public function standard()
    {
        return $this->belongsTo(Standard::class);
    }
}
