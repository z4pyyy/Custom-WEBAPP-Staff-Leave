<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Leave; 
use Illuminate\Http\Request;
use App\Notifications\LeaveRequestNotification;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

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
            'day_length' => $request->day_length,
        ]);

        $leave->load('user');

        // 通知管理层
        $admins = User::whereIn('role_id', [1, 2])->get();
        foreach ($admins as $admin) {
            $admin->notify(new LeaveRequestNotification($leave));
        }
        
        app('firebase')->push('leaves', [
            'id' => $leave->id,
            'user_id' => $leave->user_id,
            'user_name' => $leave->user->name ?? 'Unknown',
            'type' => $leave->type,
            'start_date' => $leave->start_date,
            'end_date' => $leave->end_date,
            'reason' => $leave->reason,
            'status' => $leave->status,
            'created_at' => $leave->created_at->toDateTimeString(),
        ]);

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

        // 处理点击通知时自动标记为已读
        if (request()->has('notification_id')) {
            $notification = DatabaseNotification::find(request()->get('notification_id'));
            if ($notification && $notification->notifiable_id === auth()->id()) {
                $notification->markAsRead();
            }
        }

        return view('leave.approve', compact('leave'));
    }

    public function approve($id)
    {
        $leave = Leave::findOrFail($id);
        $leave->status = 'Approved';
        $leave->rejection_reason = null;
        $leave->save();
        $leave->user->notify(new LeaveRequestNotification($leave, 'Your leave request has been approved.'));

        // Firebase leave status
        app('firebase')->set("leaves/{$leave->id}", [
            'id' => $leave->id,
            'user_id' => $leave->user_id,
            'user_name' => $leave->user->name ?? 'Unknown',
            'type' => $leave->type,
            'start_date' => $leave->start_date,
            'end_date' => $leave->end_date,
            'reason' => $leave->reason,
            'status' => $leave->status,
            'rejection_reason' => null,
            'created_at' => $leave->created_at->toDateTimeString(),
        ]);

        return redirect()->route('leave.manage')->with('success', 'Leave approved.');
    }

    public function reject(Request $request)
    {
        $request->validate([
            'leave_id' => 'required|exists:leaves,id',
            'rejection_reason' => 'nullable|string|max:1000',
        ]);

        $leave = Leave::findOrFail($request->leave_id);
        $leave->status = 'Rejected';
        $leave->rejection_reason = $request->input('rejection_reason');
        $leave->save();
        $leave->user->notify(new LeaveRequestNotification($leave, 'Your leave request has been rejected.'));

        // Firebase leave status
        app('firebase')->set("leaves/{$leave->id}", [
            'id' => $leave->id,
            'user_id' => $leave->user_id,
            'user_name' => $leave->user->name ?? 'Unknown',
            'type' => $leave->type,
            'start_date' => $leave->start_date,
            'end_date' => $leave->end_date,
            'reason' => $leave->reason,
            'status' => $leave->status,
            'rejection_reason' => $leave->rejection_reason,
            'created_at' => $leave->created_at->toDateTimeString(),
        ]);

        return redirect()->route('leave.manage')->with('success', 'Leave rejected.');
    }

    public function manage(Request $request)
    {
        if (!in_array(auth()->user()->role_id, [1, 2])) {
            abort(403);
        }

        $query = Leave::with('user');

        // 搜索：姓名
        if ($request->filled('name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        // 搜索：开始日期
        if ($request->filled('start_date')) {
            $query->whereDate('start_date', $request->start_date);
        }

        // 搜索：状态
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 自定义状态排序
        $leaves = $query->orderByRaw("
            CASE 
                WHEN status = 'Pending' THEN 1
                WHEN status = 'Rejected' THEN 2
                WHEN status = 'Approved' THEN 3
                ELSE 4
            END
        ")
        ->orderByDesc('created_at')
        ->get();

        return view('leave.manage', compact('leaves'));
    }



    public function calendar()
    {
        return view('leave.calendar');
    }

    public function calendarData(): JsonResponse
    {
        $user = Auth::user();
        $query = Leave::query();

        // 权限判断
        if ($user->role === 'Employee') {
            $query->where('user_id', $user->id);
        }

        $leaves = $query->where('status', 'Approved')->with('user')->get();

        $events = $leaves->map(function ($leave) {
            $color = match (strtolower($leave->type)) {
                'annual' => '#008000',      // Green
                'medical' => '#2196f3',     // Blue
                'replacement' => '#ff9800', // Orange
                'unpaid' => '#f44336',      // Red
                default => '#9e9e9e',       // Grey fallback
            };

            return [
                'title' => $leave->user->name . ' - ' . $leave->type,
                'start' => $leave->start_date,
                'end' => date('Y-m-d', strtotime($leave->end_date . ' +1 day')), // FullCalendar exclusive end
                'color' => $color, 
                'extendedProps' => [
                    'reason' => $leave->reason,
                    'status' => $leave->status,
                ]
            ];
        });

        return response()->json($events);
    }


    public function report(Request $request)
    {
        $query = Leave::with('user');

        // 筛选：月份
        if ($request->filled('month')) {
            $query->whereMonth('start_date', $request->month);
        }

        // 筛选：年份
        if ($request->filled('year')) {
            $query->whereYear('start_date', $request->year);
        }

        // 筛选：类型
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // 筛选：员工
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $leaves = $query->orderBy('start_date', 'desc')->get();
        $users = User::all();

        return view('leave.report', compact('leaves', 'users'));
    }

    //History
    public function history(Request $request)
    {
        $user = auth()->user();
        $query = Leave::with('user');

        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        // Year Filter
        if ($request->filled('year')) {
            $query->whereYear('start_date', $request->year);
        }

        // Month Filter
        if ($request->filled('month')) {
            $query->whereMonth('start_date', $request->month);
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', strtolower($request->status));
        }

        $leaves = $query->latest()->paginate(10);
        return view('leave.history', compact('leaves'));
    }

}


