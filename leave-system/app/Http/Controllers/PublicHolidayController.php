<?php

namespace App\Http\Controllers;

use App\Models\PublicHoliday;
use Illuminate\Http\Request;

class PublicHolidayController extends Controller
{
    public function index(Request $request)
    {
        $query = PublicHoliday::query();
        $year = $request->input('year', now()->year);
        $query->whereYear('date', $year);

        $holidays = $query->orderBy('date')->paginate(10);
        $years = PublicHoliday::selectRaw('YEAR(date) as year')->distinct()->pluck('year');

        return view('public_holiday.index', compact('holidays', 'year', 'years'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'date' => 'required|date',
        ]);
        PublicHoliday::create([
            'name' => $request->name,
            'date' => $request->date,
            'year' => date('Y', strtotime($request->date))
        ]);
        return redirect()->route('public_holiday.index')->with('success', 'Public holiday added.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'date' => 'required|date',
        ]);
        $holiday = PublicHoliday::findOrFail($id);
        $holiday->update([
            'name' => $request->name,
            'date' => $request->date,
            'year' => date('Y', strtotime($request->date))
        ]);
        return redirect()->route('public_holiday.index')->with('success', 'Public holiday updated.');
    }

    public function destroy($id)
    {
        $holiday = PublicHoliday::findOrFail($id);
        $holiday->delete();
        return redirect()->route('public_holiday.index')->with('success', 'Public holiday deleted.');
    }
}
