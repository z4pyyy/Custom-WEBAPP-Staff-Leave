<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LeaveCancelledNotification extends Notification
{
    use Queueable;

    protected $leave;
    protected $cancelledBy;

    public function __construct($leave)
    {
        $this->leave = $leave;
        $this->cancelledBy = auth()->user()->name; 
    }

    public function via($notifiable)
    {
        return ['database']; // 可加 mail/slack etc
    }

    public function toDatabase($notifiable)
    {
        return [
            'message' => 'Leave has been cancelled by ' . $this->cancelledBy,
            'leave_id' => $this->leave->id,
        ];
    }
}
