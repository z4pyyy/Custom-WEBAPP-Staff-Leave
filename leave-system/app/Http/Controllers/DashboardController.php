<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnnualLeaveBalance;
use App\Models\Leave;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user();
        $year = now()->year;
        $month = now()->month;

        // 当前年starting balance
        $balance = AnnualLeaveBalance::where('user_id', $user->id)
            ->where('year', $year)
            ->first();

        $starting_balance = $balance ? $balance->starting_balance : 0;

        // 根据做工月份+1天/月
        $earned_this_year = $month; // 每月1天
        $total_earned = $starting_balance + $earned_this_year;

        // 统计用掉多少天
        $annual_taken = Leave::where('user_id', $user->id)
            ->where('type', 'Annual')
            ->whereYear('start_date', $year)
            ->where('status', 'approved')
            ->sum('day_length');

        $annual_balance = max(0, $total_earned - $annual_taken);

        return view('dashboard', compact('starting_balance', 'annual_balance', 'annual_taken', 'total_earned', 'earned_this_year'));
    }
}
