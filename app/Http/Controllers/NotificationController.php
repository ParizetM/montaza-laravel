<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class NotificationController extends Controller
{
    public function lu(int $id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(attributes: ['read' => true]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {


        if ($request->ajax()) {
            $allNotifications = Auth::user()->notifications()->orderBy('created_at', 'desc')->paginate(10);

            // Séparer les notifications non lues et lues et le type
            $notifications = $allNotifications->where('read', false);
            $notificationsSystem = $notifications->where('type', 'system');
            $notifications_readed = $allNotifications->where('read', true)->sortByDesc('updated_at');
            if ($request->tab == 'tab1') {
                $notificationsRendu = $notifications;
                $specifyType = true;
            } else if ($request->tab == 'tab2') {
                $notificationsRendu = $notificationsSystem;
                $specifyType = false;

            } else if ($request->tab == 'tab3') {
                $notificationsRendu = $notifications_readed;
                $specifyType = true;
            }
            return view('notifications.partials._notifications', [
                'notifications' => $notificationsRendu,
                'specifyType' => $specifyType
            ])->render();
        }
        $allNotifications = Auth::user()->notifications()->orderBy('created_at', 'desc')->paginate(10);

        // Séparer les notifications non lues et lues et le type
        $notifications = $allNotifications->where('read', false);
        $notificationsSystem = $notifications->where('type', 'system');
        $notifications_readed = $allNotifications->where('read', true)->sortByDesc('updated_at');
        return view('notifications.index', [
            'notifications' => $notifications,
            'notificationsSystem' => $notificationsSystem,
            'notifications_readed' => $notifications_readed,
        ]);
    }



    // /**
    //  * Show the form for creating a new resource.
    //  */
    // public function create()
    // {
    //     //
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(Request $request)
    // {
    //     //
    // }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(Notification $notification)
    // {
    //     //
    // }

    // /**
    //  * Show the form for editing the specified resource.
    //  */
    // public function edit(Notification $notification)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request, Notification $notification)
    // {
    //     //
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(Notification $notification)
    // {
    //     //
    // }
}
