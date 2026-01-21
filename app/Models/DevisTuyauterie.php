<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DevisTuyauterie extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'date_emission' => 'date',
        'options' => 'array',
    ];

    public function sections()
    {
        return $this->hasMany(DevisTuyauterieSection::class);
    }

    public function societe()
    {
        return $this->belongsTo(Societe::class);
    }

    public function societeContact()
    {
        return $this->belongsTo(SocieteContact::class);
    }
}
