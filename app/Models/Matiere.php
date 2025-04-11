<?php

namespace App\Models;

use App\MatiereMouvement;
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
        'standard_version_id',
        'stock_min',
        'ref_valeur_unitaire',
        'material_id',
    ];

    protected static function booted()
    {
        static::created(function ($matiere) {
            Stock::create([
                'matiere_id' => $matiere->id,
                'quantite' => 0,
                'valeur_unitaire' => 0,
            ]);
        });
    }
    public function societes()
    {
        return $this->belongsToMany(Societe::class, 'societe_matieres')
            ->withTimestamps();
    }
    public function fournisseurs()
    {
        return $this->belongsToMany(Societe::class, 'societe_matieres')
            ->whereIn('societe_type_id', ['3', '2'])
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
        return $this->hasOneThrough(Standard::class, StandardVersion::class, 'id', 'id', 'standard_version_id', 'standard_id');
    }
    public function material()
    {
        return $this->belongsTo(Material::class);
    }
    public function typeAffichageStock(): int
    {
        return $this->sousFamille->type_affichage_stock;
    }
    public function quantite()
    {
        if ($this->typeAffichageStock() === 1) {
            return $this->stock->sum('quantite');
        } elseif ($this->typeAffichageStock() === 2) {
            $quantite = 0;
            foreach($this->stock as $stock) {
                $quantite += $stock->quantite * $stock->valeur_unitaire;
            };
            return $quantite;
        } else {
            return $this->stock->sum('quantite');
        }
    }
    public function societeMatieres()
    {
        return $this->hasMany(SocieteMatiere::class);
    }
    public function societeMatiere($societeId)
    {
        return $this->hasOne(SocieteMatiere::class, 'matiere_id', 'id')->where('societe_id', $societeId)->first();
    }
    public function prix()
    {
        return $this->hasManyThrough(
            SocieteMatierePrix::class, // Table cible (les prix)
            SocieteMatiere::class, // Table pivot (associe matières et sociétés)
            'matiere_id', // Clé étrangère sur `societe_matieres`
            'societe_matiere_id', // Clé étrangère sur `societe_matiere_prixs`
            'id', // Clé primaire de `matieres`
            'id' // Clé primaire de `societe_matieres`
        );
    }
    /**
     * Summary of prixPourSociete
     * @param mixed $societeId
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough<SocieteMatierePrix, SocieteMatiere, Matiere>
     */
    public function prixPourSociete($societeId)
    {
        return $this->prix()->whereHas('societeMatiere', function ($query) use ($societeId) {
            $query->where('societe_id', $societeId);
        });
    }
    public function getLastPrice($societe_id = null)
    {
        if ($societe_id) {
            return $this->prixPourSociete($societe_id)->latest()->first();
        } else {
            return $this->prix()->latest()->first();
        }
    }

    public function stock() {
        return $this->hasMany(Stock::class);
    }
    public function mouvementStocks()
    {
        return $this->hasMany(MouvementStock::class);
    }
    public function getLastMouvementStock()
    {
        return $this->mouvementStocks()->latest()->first();
    }
}
