<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Leave; // 你需要先创建 Leave 模型
use Illuminate\Http\Request;
use App\Notifications\LeaveRequestNotification;
use Illuminate\Notifications\DatabaseNotification;

class LeaveController extends Controller
{
    public function index()
    {
        $leaves = Leave::where('user_id', auth()->id())->get();
        return view('leave.index', compact('leaves'));
    }

    public function create()
    {
        return view('leave.apply');
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date',
            'type' => 'required|string',
            'reason' => 'nullable|string',
        ]);

        $leave = Leave::create([
            'user_id' => auth()->id(),
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'type' => $request->type,
            'reason' => $request->reason,
            'status' => 'Pending',
        ]);

        // ✅ 确保 leave 带上 user 关系
        $leave->load('user');

        // 🔔 通知管理层
        $admins = User::whereIn('role_id', [1, 2])->get();
        foreach ($admins as $admin) {
            $admin->notify(new LeaveRequestNotification($leave));
        }

        // 🔔 发送通知给所有管理员
        $admins = User::whereIn('role_id', [1, 2])->get(); // Admin and Management
        foreach ($admins as $admin) {
            $admin->notify(new LeaveRequestNotification($leave));
        }

        return redirect()->route('leave.index')->with('success', 'Leave request submitted.');
    }

    public function showApprovalForm($id)
    {
        $leave = Leave::findOrFail($id);
        return view('leave.approve', compact('leave'));
    }

    public function approvePage($id)
    {
        $leave = Leave::findOrFail($id);

        // ✅ 处理点击通知时自动标记为已读
        if (request()->has('notification_id')) {
            $notification = DatabaseNotification::find(request()->get('notification_id'));
            if ($notification && $notification->notifiable_id === auth()->id()) {
                $notification->markAsRead();
            }
        }

        return view('leave.approve', compact('leave'));
    }
}
