<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    //
    
    public function index()
    {
        $notifications = auth()->user()->notifications()->latest()->paginate(10);
        return view('notifications.index', compact('notifications'));
    }

    public function read($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        // 可以重定向去 leave/approve/{id}
        return redirect()->route('leave.approve', $notification->data['leave_id']);
    }

    public function markAllRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }

}
