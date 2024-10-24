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
        $notification->update(['read' => true]);
        return redirect()->back();
    }
    public function nonLu(int $id)
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['read' => false]);
        return redirect()->back();
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $activeTab = $request->activeTab ?? 'tab1';
        $notifications = Auth::user()->notifications()->where('read', false)->orderBy('created_at', 'desc')->paginate(20);
        $notificationsSystem = $notifications->where('type', 'system');
        if ($request->ajax()) {

            if ($request->tab == 'tab1') {
                $notificationsRendu = $notifications;
                $specifyType = true;
            } else if ($request->tab == 'tab2') {
                $notificationsRendu = $notificationsSystem;
                $specifyType = false;
            }
            return view('notifications.partials._notifications', [
                'notifications' => $notificationsRendu,
                'specifyType' => $specifyType
            ])->render();
        }
        // dd($notifications); // Debugging statement removed
        return view('notifications.index', [
            'notifications' => $notifications,
            'notificationsSystem' => $notificationsSystem,
            'activeTab' => $activeTab
        ]);
    }
    public function indexLus(Request $request)
    {
        $notifications_readed = Auth::user()->notifications()->where('read', true)->orderBy('updated_at', 'desc')->paginate(20);
        if ($request->ajax()) {
                $specifyType = true;
            return view('notifications.partials._notifications', [
                'notifications' => $notifications_readed,
                'specifyType' => $specifyType
            ])->render();
        }
        // dd($notifications); // Debugging statement removed
        return view('notifications.lus', [
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
