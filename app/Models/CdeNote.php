<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CdeNote extends Model
{
    protected $fillable = [
        'contenu',
        'ordre',
        'is_checked',
        'entite_id',
    ];

    public function cde()
    {
        return $this->belongsTo(Cde::class);
    }

    public function cdenote()
    {
        return $this->hasMany(CdeNote::class);
    }
    public function cdeCdeNote()
    {
        return $this->hasMany(CdeCdeNote::class);
    }
    public function entite()
    {
        return $this->belongsTo(Entite::class);
    }
}
