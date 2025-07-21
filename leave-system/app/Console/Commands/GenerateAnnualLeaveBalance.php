<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\AnnualLeaveBalance;
use App\Models\Leave;

class GenerateAnnualLeaveBalance extends Command
{
    protected $signature = 'generate:annualleavebalance';
    protected $description = 'Generate Annual Leave Balance for New Year';

    public function handle()
    {
        $year = now()->year;
        $lastYear = $year - 1;

        $users = User::all();

        foreach ($users as $user) {
            $last_balance = AnnualLeaveBalance::where('user_id', $user->id)->where('year', $lastYear)->first();
            $starting_balance = $last_balance ? $last_balance->starting_balance : 0;

            $earned_last_year = 12;
            $used_last_year = Leave::where('user_id', $user->id)->where('type', 'Annual')->whereYear('start_date', $lastYear)->where('status', 'approved')->sum('day_length');

            $last_year_remaining = max(0, ($starting_balance + $earned_last_year) - $used_last_year);
            $carry_forward = min($last_year_remaining, 5);
            $new_starting_balance = $carry_forward + 1; // 1月+1天

            AnnualLeaveBalance::updateOrCreate([
                'user_id' => $user->id,
                'year' => $year,
            ], [
                'starting_balance' => $new_starting_balance,
            ]);
        }

        $this->info("✅ Annual leave balance for {$year} updated.");
    }
}
