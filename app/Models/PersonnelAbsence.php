<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonnelAbsence extends Model
{
    use HasFactory;

    protected $fillable = [
        'personnel_id',
        'date',
        'type',
        'duree',
        'periode',
        'minutes_retard',
        'motif',
        'statut',
    ];

    protected $casts = [
        'date' => 'date',
        'minutes_retard' => 'integer',
    ];

    public function personnel()
    {
        return $this->belongsTo(Personnel::class);
    }

    public function getTypeLibelleAttribute(): string
    {
        return match($this->type) {
            'retard'  => 'Retard',
            'absence' => 'Absence',
            'maladie' => 'Maladie',
            'autre'   => 'Autre',
            default   => $this->type,
        };
    }

    public function getDureeLibelleAttribute(): string
    {
        if ($this->type === 'retard') {
            $h = intdiv((int) $this->minutes_retard, 60);
            $m = (int) $this->minutes_retard % 60;
            return $h > 0 ? "{$h}h{$m}min" : "{$m}min";
        }
        if ($this->duree === 'demi_journee') {
            $periode = $this->periode === 'apres_midi' ? 'après-midi' : 'matin';
            return "½ journée ({$periode})";
        }
        return match($this->duree) {
            'journee_complete' => 'Journée complète',
            default            => '-',
        };
    }
}
