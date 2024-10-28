<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class Permission extends Model
{
    use HasFactory;

    protected static function booted()
    {
        // Enregistrer avant la création d'un modèle
        static::created(function ($model) {
            self::logChange($model, 'creating');
        });

        // Enregistrer avant la mise à jour d'un modèle
        static::updating(function ($model) {
            self::logChange($model, 'updating');
        });

        // Enregistrer avant la suppression d'un modèle
        static::deleting(function ($model) {
            self::logChange($model, 'deleting');
        });
    }

    protected static function logChange($model, $event)
    {
        ModelChange::create([
            'user_id' => Auth::id(),
            'model_type' => 'Permissions',
            'model_id' => $model->getKey(),
            'before' => $model->getOriginal(),
            'after' => $model->getAttributes(),
            'event' => $event,
        ]);
    }
    // Relation avec les rôles
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
