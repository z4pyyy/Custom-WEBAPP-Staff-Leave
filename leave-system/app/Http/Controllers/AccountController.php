<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('account.settings', compact('user'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'birthday' => 'nullable|date',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // 限2MB
        ]);

        $user = Auth::user();

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $avatarPath;
        }

        $user->name = $request->name;
        $user->department = $request->department;
        $user->birthday = $request->birthday;
        $user->gender = $request->gender;
        $user->save();

        return redirect()->route('account.settings')->with('success', 'Profile updated successfully!');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return redirect()->route('account.settings')->with('success', 'Password changed successfully.');
    }
}
