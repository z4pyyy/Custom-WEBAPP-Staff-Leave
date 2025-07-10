<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FirebaseService;

class UserController extends Controller
{
    protected $firebase;

    public function __construct(FirebaseService $firebase)
    {
        $this->firebase = $firebase;
    }

    public function create()
    {
        return view('admin.users.create');
    }

    // Handle form submission to store new user
    public function store(Request $request)
    {

        // Check if email already exists
        $existingUsers = $this->firebase->getUsers();
        foreach ($existingUsers ?? [] as $user) {
            if (($user['email'] ?? '') === $request->input('email')) {
                return redirect()->back()->withInput()->withErrors(['email' => 'This email is already in use.']);
            }
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'role_id' => 'required|integer|in:1,2,3',
        ]);

        $userData = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role_id'  => (int) $request->input('role_id'),
            'password' => bcrypt('homsliving123'),
        ];

        $this->firebase->addUser($userData);

        return redirect()->route('admin.users')->with('success', 'New user added!');
    }


    // Display all users
    public function index()
    {
        $rawUsers = $this->firebase->getUsers();

        $users = collect($rawUsers)->map(function ($data, $key) {
            $data['id'] = $key;
            return (object) $data;
        });

        return view('admin.users.index', compact('users'));
    }


    public function edit($id)
    {
        $user = $this->firebase->getUser($id);

        if (!$user) {
            abort(404);
        }

        if (($user['role'] ?? '') === 'superadmin') {
            return redirect()->back()->with('error', 'You cannot edit the Superadmin.');
        }

        $user['id'] = $id;
        return view('admin.users.edit', [
            'user' => (object) $user,
            'id' => $id
        ]);
    }

    public function destroy($id)
    {
        $user = $this->firebase->getUser($id);

        if (($user['role'] ?? '') === 'superadmin') {
            return redirect()->back()->with('error', 'You cannot delete the Superadmin.');
        }

        $this->firebase->deleteUser($id);
        return redirect()->route('admin.users')->with('success', 'User deleted!');
    }



    // Update a user
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'role' => 'required|string',
        ]);

        $this->firebase->updateUser($id, [
            'name'  => $request->input('name'),
            'email' => $request->input('email'),
            'role'  => $request->input('role'),
        ]);

        return redirect()->route('admin.users')->with('success', 'User updated successfully!');
    }
}
