<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Log;

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
            ->withPivot(['ref_fournisseur', 'designation_fournisseur', 'prix', 'unite_id', 'date_dernier_prix'])
            ->withTimestamps();
    }
    public function getLastPrice($societe_id = null)
    {
        // Log::info('societe_id: ' . $societe_id);
        if ($societe_id) {
            $lastPrice = $this->fournisseurs()
                ->where('societe_id', $societe_id)
                ->whereNotNull('prix')
                ->whereNotNull('unite_id')
                ->orderBy('date_dernier_prix', 'desc')
                ->first();
            if (!$lastPrice) {
                $lastPrice = $this->fournisseurs()
                    ->where('societe_id', $societe_id)
                    ->orderBy('date_dernier_prix', 'desc')
                    ->first();
            }
        } else {
            $lastPrice = $this->fournisseurs()
                ->whereNotNull('prix')
                ->whereNotNull('unite_id')
                ->orderBy('date_dernier_prix', 'desc')
                ->first();

            if (!$lastPrice) {
                $lastPrice = $this->fournisseurs()
                    ->orderBy('date_dernier_prix', 'desc')
                    ->first();
            }
        }

        return $lastPrice;
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
