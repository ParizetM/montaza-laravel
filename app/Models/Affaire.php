<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Notification;
use Auth;

class Affaire extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'nom',
        'total_ht',
        'budget',
        'budget_notified',
        'created_at',
        'updated_at',
    ];

    /**
     * Génère le prochain code pour une nouvelle affaire
     * Format: YY-XXX où YY est l'année courante et XXX est un numéro incrémenté
     *
     * @return string
     */
    public static function generateNextCode()
    {
        $year = date('y'); // Année courante en format 2 chiffres (ex: 25 pour 2025)

        // Récupérer le code le plus élevé pour l'année courante
        $lastAffaire = self::where('code', 'LIKE', $year . '-%')
            ->orderByRaw('CAST(SUBSTRING(code, 4) AS INTEGER) DESC')
            ->first();

        if ($lastAffaire) {
            // Extraire la partie numérique et l'incrémenter
            $parts = explode('-', $lastAffaire->code);
            $nextNumber = intval($parts[1]) + 1;
        } else {
            // Première affaire de l'année
            $nextNumber = 1;
        }

        // Formatage du nouveau code avec des zéros en début de partie numérique (ex: 25-001)
        return $year . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Relation avec les commandes (Cde)
     */
    public function cdes()
    {
        return $this->hasMany(Cde::class, 'affaire_id');
    }

    public function updateTotal()
    {
        $this->total_ht = $this->cdes->where('ddp_cde_statut_id', 5)->sum('total_ht');
        if ($this->total_ht <= 0 || is_null($this->total_ht)) {
            $this->total_ht = 0; // Si le total est 0, on le met à null
        }

        if ($this->budget && $this->total_ht > $this->budget && !$this->budget_notified) {
            try {
                Notification::createNotification(
                    Auth::user()->role,
                    'system',
                    'Budget dépassé',
                    "Le total HT ({$this->total_ht}) dépasse le budget ({$this->budget}) de l'affaire {$this->code}.",
                    'Vérifier le budget',
                    'affaires.show',
                    ['affaire' => $this->id],
                    'aller voir'
                );
                $this->budget_notified = true; // Marquer comme notifié
            } catch (\Exception $e) {
                // Log l'erreur ou gérer selon le besoin
                \Log::error('Erreur lors de la création de la notification : ' . $e->getMessage());
            }
        } else {
            $this->budget_notified = false; // Réinitialiser si le budget n'est pas dépassé
        }

        // Sauvegarder les modifications
        $this->save();
    }
}
