<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MediaType extends Model
{
    protected $table = 'media_types';

    protected $fillable = [
    'nom',
    'background_color_light',
    'background_color_dark',
    'text_color_light',
    'text_color_dark',
];
    protected static function booted()
    {
        static::addGlobalScope('orderByNom', function ($query) {
            $query->orderBy('nom');
        });
    }

    public function media(): HasMany
    {
        return $this->hasMany(Media::class, 'media_type_id');
    }
}
