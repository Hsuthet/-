<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
   public function index(Request $request)
{
    $query = User::with('department');

    // Apply filter if 'role' is present in the URL
    if ($request->filled('role')) {
        $query->where('role', $request->role);
    }

    $users = $query->latest()->get();

    return view('admin.users.index', compact('users'));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
{
    $departments = Department::all();
    return view('admin.users.create', compact('departments'));
}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:8',
        'department_id' => 'required',
        'role' => 'required|in:admin,manager,employee',
    ]);

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'department_id' => $request->department_id,
        'role' => $request->role,
    ]);

    return redirect()->route('users.index')->with('success', 'User created');
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
   public function edit(User $user)
{
    $departments = Department::all();
    return view('admin.users.edit', compact('user', 'departments'));
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'department_id' => 'required',
        'role' => 'required|in:admin,manager,employee',
    ]);

    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'department_id' => $request->department_id,
        'role' => $request->role,
    ]);

    return redirect()->route('users.index')->with('success', 'User updated');
}

    /**
     * Remove the specified resource from storage.
     */
  public function destroy(User $user)
{
    $user->delete();
    return back()->with('success', 'User deleted');
}
}
