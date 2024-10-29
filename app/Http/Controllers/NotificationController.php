<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Redirect;

class NotificationController extends Controller
{
    public function lu(int $id): RedirectResponse
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['read' => true]);
        return redirect()->back();
    }

    public function luAll(string $type = null): RedirectResponse
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'Vous devez être connecté pour effectuer cette action');
        }
        if ($type) {

            $user->notifications()->where('type', $type)->update(['read' => true]);
        } else {
            $user->notifications()->update(['read' => true]);
        }
        return redirect()->back();
    }

    public function nonLu(int $id): RedirectResponse
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['read' => false]);
        return redirect()->route('notifications.index');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): RedirectResponse|View|string
    {
        $activeTab = $request->activeTab ?? 'tab1';
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'Vous devez être connecté pour effectuer cette action');
        }
        $notifications = $user->notifications()->where('read', false)->orderBy('created_at', 'desc')->paginate(20);
        $notificationsSystem = $notifications->where('type', 'system');
        if ($request->ajax()) {

            if ($request->tab == 'tab1') {
                $notificationsRendu = $notifications;
                $specifyType = true;
            } else if ($request->tab == 'tab2') {
                $notificationsRendu = $notificationsSystem;
                $specifyType = false;
            } else {
                $notificationsRendu = $notifications;
                $specifyType = true;
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
    public function indexLus(Request $request): View|RedirectResponse|string
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->back()->with('error', 'Vous devez être connecté pour effectuer cette action');
        }
        $notifications_readed = $user->notifications()->where('read', true)->orderBy('updated_at', 'desc')->paginate(20);
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
    public function detail(int $id): View
    {
        $notification = Notification::findOrFail($id);
        $notification->update(['read' => true]);
        return view('notifications.detail', [
            'notification' => $notification
        ]);
    }
    public function transfer(Request $request): RedirectResponse
    {
        $id = $request->role_id_notif;
        $notification_id = $request->notification_id;
        $notification = Notification::findOrFail($notification_id);
        if ($notification instanceof Notification) {
            $notification->update(['role_id' => $id]);
        }else{
            return redirect()->back()->with('error', 'Notification introuvable');
        }
        return redirect()->back();
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
