<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory>  */
    use HasFactory, Notifiable;

    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [

        'id',
        'last_name',
        'first_name',
        'phone',
        'email',
        'role_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected static function booted()
    {
        // Enregistrer avant la création d'un modèle
        static::created(function ($model) {
            self::logChange($model, 'creating');
        });

        // Enregistrer avant la mise à jour d'un modèle
        static::updating(function ($model) {
            if ($model->isDirty('remember_token')) {
            return;
            }
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
            'model_type' => 'Utilisateurs',
            'model_id' => $model->getKey(),
            'before' => $model->getOriginal(),
            'after' => $model->getAttributes(),
            'event' => $event,
        ]);
    }

    /**
     * Summary of hasRole
     */
    public function hasRole(int $role_id): bool
    {
        return $this->role_id === $role_id;
    }

    /**
     * Summary of role
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<\App\Models\Role, \App\Models\User>
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(related: Role::class, foreignKey: 'role_id');
    }

    public function permissions(): mixed
    {
        return $this->role->permissions ?? collect(); // Si pas de rôle, renvoie une collection vide
    }

    public function hasPermission(string $permission): bool
    {
        return $this->permissions()->contains('name', $permission);
    }
    public function notifications()
    {
        return $this->role->hasMany(Notification::class);
    }
}
