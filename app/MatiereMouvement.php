<?php

namespace App;

use App\Models\Matiere;
use Illuminate\Database\Eloquent\Model;


class MatiereMouvement extends Model
{
    /**
     * Create a new class instance.
     */
    protected $fillable = [
        'quantite', 'type_mouvement'
    ];
    public function matiere()
    {
        return $this->belongsTo(Matiere::class);
    }
}
