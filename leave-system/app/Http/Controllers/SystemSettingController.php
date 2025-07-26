<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use App\Models\AnnualLeaveRule;
use App\Models\SystemInfo;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    public function index()
    {
        if (!in_array(auth()->user()->role_id, [1,2])) abort(403);

        $leave_types = LeaveType::all();
        $annual_rule = AnnualLeaveRule::first();
        $system_info = SystemInfo::first();

        return view('system.index', compact('leave_types', 'annual_rule', 'system_info'));
    }

    public function updateAnnualLeave(Request $request)
    {
        $request->validate([
            'max_carry_forward_days' => 'required|integer|min:0',
            'monthly_earning_rate' => 'required|numeric|min:0',
        ]);

        AnnualLeaveRule::updateOrCreate(
            ['id' => 1],
            $request->only('max_carry_forward_days', 'monthly_earning_rate')
        );

        return back()->with('success', 'Annual Leave Rules updated.');
    }

    public function updateSystemInfo(Request $request)
    {
        $request->validate([
            'system_name' => 'required|string',
            'company_name' => 'required|string',
        ]);

        SystemInfo::updateOrCreate(
            ['id' => 1],
            $request->only('system_name', 'company_name')
        );

        return back()->with('success', 'System Info updated.');
    }

    public function storeLeaveType(Request $request)
    {
        $request->validate(['name' => 'required|string|unique:leave_types,name']);
        LeaveType::create(['name' => $request->name]);
        return back()->with('success', 'Leave Type added.');
    }

    public function deleteLeaveType($id)
    {
        LeaveType::findOrFail($id)->delete();
        return back()->with('success', 'Leave Type deleted.');
    }
}

