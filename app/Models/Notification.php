<?php

namespace App\Models;

use Database\Factories\NotificationFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Role;
use Route;

class Notification extends Model
{
    /** @use HasFactory<NotificationFactory>  */
    use HasFactory;
    use SoftDeletes;
    /**
     * Description de la fonction.
     *
     * @param \App\Models\Role $role role cible de la notification.
     * @param string $type type de notification ex: system en anglais.
     * @param string $title titre de la notification.
     * @param string $message message de la notification.
     * @param string $action_requise action requise pour la notification si nul pas d'action requise.
     * @param \Route|null $route route à suivre pour l'action requise.
     * @param string $label label du bouton pour l'action requise.
     */
    public static function createNotification(Role $role, string $type, string $title, string $message = null, string $action_requise = null, string $route_nom = null,array $route_data = null, string $label = 'aller voir')
    {
        $notification = new self();
        $notification->role_id = $role->id;
        $notification->type = $type;
        $data = [
            'title' => $title,
        ];

        if ($message !== null) {
            $data['message'] = $message;
        }

        if ($action_requise !== null) {
            $data['action_requise'] = $action_requise;
        }

        if ($route_nom !== null && $route_data !== null && $label !== null && $action_requise !== null) {
            $data['action'] = [
                'route_nom' => $route_nom,
                'route_data' => $route_data,
                'label' => $label
            ];
        }

        $notification->data = json_encode($data);
        $notification->read = false;
        $notification->save();
        return $notification;
    }
    /**
     * Summary of fillable
     * @var array
     */
    protected $fillable = [
        'role_id',
        'type',
        'data',
        'read',
    ];
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
