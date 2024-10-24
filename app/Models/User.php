<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

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
