<?php

namespace App\Http\Controllers;

use App\Models\AnnualLeaveBalance;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\AnnualLeaveBalanceAudit;

class AnnualLeaveBalanceController extends Controller
{
    public function index(Request $request)
    {
        if (!in_array(auth()->user()->role_id, [1, 2])) abort(403);

        $query = AnnualLeaveBalance::with('user');

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        if ($request->filled('name')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        $balances = $query->orderBy('year', 'desc')->paginate(10);
        $users = User::all();
        $years = range(now()->year, now()->year + 1);

        return view('annual_balance.index', compact('balances', 'users', 'years'));
    }

    public function store(Request $request)
    {
        if (!in_array(auth()->user()->role_id, [1, 2])) abort(403);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'year' => 'required|integer',
            'starting_balance' => 'required|numeric|min:0',
        ]);

        $exists = AnnualLeaveBalance::where('user_id', $validated['user_id'])
                    ->where('year', $validated['year'])
                    ->exists();
        if ($exists) {
            return redirect()->back()->with('error', 'Record already exists.');
        }

        AnnualLeaveBalance::create($validated);

        AnnualLeaveBalanceAudit::create([
            'user_id' => $validated['user_id'],
            'action_by' => auth()->id(),
            'action' => 'create',
            'description' => 'Added starting balance '.$validated['starting_balance'].' days for year '.$validated['year'],
        ]);

        return redirect()->route('balance.index')->with('success', 'Record added successfully.');
    }

    public function update(Request $request)
    {
        if (!in_array(auth()->user()->role_id, [1, 2])) abort(403);

        $request->validate([
            'balance_id' => 'required|exists:annual_leave_balances,id',
            'starting_balance' => 'required|numeric|min:0',
        ]);

        $balance = AnnualLeaveBalance::find($request->balance_id);
        $balance->starting_balance = $request->starting_balance;
        $balance->save();

        AnnualLeaveBalanceAudit::create([
            'user_id' => $balance->user_id,
            'action_by' => auth()->id(),
            'action' => 'update',
            'description' => 'Updated starting balance to '.$request->starting_balance.' days for year '.$balance->year,
        ]);

        return redirect()->route('balance.index')->with('success', 'Balance updated successfully.');
    }

    public function destroy($id)
    {
        if (!in_array(auth()->user()->role_id, [1, 2])) abort(403);

        $balance = AnnualLeaveBalance::findOrFail($id);
        $balance->delete();

        AnnualLeaveBalanceAudit::create([
            'user_id' => $balance->user_id,
            'action_by' => auth()->id(),
            'action' => 'delete',
            'description' => 'Deleted starting balance record for year '.$balance->year,
        ]);

        return redirect()->route('balance.index')->with('success', 'Record deleted successfully.');
    }

}

