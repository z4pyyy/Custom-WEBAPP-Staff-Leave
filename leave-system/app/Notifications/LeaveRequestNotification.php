<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LeaveRequestNotification extends Notification
{
    use Queueable;

    protected $leave;

    public function __construct($leave)
    {
        $this->leave = $leave;
    }

    public function via($notifiable)
    {
        return ['database']; // ✅ 用 database 渠道
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'New leave request submitted by ' . $this->leave->user->name,
            'url' => url('/leave/approve/' . $this->leave->id . '?notification_id=' . $notifiable->unreadNotifications()->first()?->id),
            'leave_id' => $this->leave->id,
            'submitted_by' => $this->leave->user->name,
            'submitted_at' => $this->leave->created_at->diffForHumans(),
        ];
    }
}
