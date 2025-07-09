<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LeaveRequestNotification extends Notification
{
    use Queueable;

    protected $leave;
    protected $customMessage;


    public function __construct($leave, $customMessage = null)
    {
        $this->leave = $leave;
        $this->customMessage = $customMessage;
    }

    public function via($notifiable)
    {
        return ['database']; 
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => $this->customMessage
                ?? 'New leave request submitted by ' . $this->leave->user->name,
            'url' => url('/leave/approve/' . $this->leave->id . '?notification_id=' . $notifiable->unreadNotifications()->first()?->id),
            'leave_id' => $this->leave->id,
            'submitted_by' => $this->leave->user->name,
            'submitted_at' => $this->leave->created_at->diffForHumans(),
        ];
    }
}
