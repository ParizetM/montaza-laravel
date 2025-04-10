<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CdeNote extends Model
{
    protected $fillable = [
        'contenu',
    ];

    public function cde()
    {
        return $this->belongsTo(Cde::class);
    }

    public function cdenote()
    {
        return $this->hasMany(CdeNote::class);
    }
}
