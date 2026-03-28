<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('department');

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->get();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $departments = Department::all();
        
        return view('admin.users.create', compact('departments'));
    }

//     public function store(Request $request)
//     {
//         $request->validate([
//     'name' => ['required', 'string', 'max:255'],
//     'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
//     'role' => ['required', 'string', 'in:admin,manager,employee'],
//     'department_id' => [
//     'exclude_if:role,admin',
//     'nullable',
//     'exists:departments,id'
// ],
//     'password' => ['required', 'confirmed', Rules\Password::defaults()],
// ]);

//         User::create([
//             'name' => $request->name,
//             'email' => $request->email,
//             'password' => Hash::make($request->password),
//             // Ensure we save NULL if they are an admin
//             'department_id' => $request->role === 'admin' ? null : $request->department_id,
//             'role' => $request->role,
//         ]);

//         return redirect()->route('users.index')->with('success');
//     }

public function store(Request $request)
{
    $request->validate([
        'employee_number' => ['required', 'string', 'max:20', 'unique:users'],
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
        'role' => ['required', 'in:admin,manager,employee'],
        'password' => ['required', 'confirmed', 'min:8'],
        // Department is required UNLESS the role is admin
        'department_id' => [
            'nullable', 
            'required_unless:role,admin', 
            'exists:departments,id'
        ],
    ]);
    $lastUser = User::latest('id')->first();
    $nextId = $lastUser ? $lastUser->id + 1 : 1;
    
    // ၃။ Format သတ်မှတ်ခြင်း (ဥပမာ: EMP-2026-001)
    // str_pad က 001, 002 စသဖြင့် ဂဏန်း ၃ လုံးပြည့်အောင် ရှေ့က ၀ ဖြည့်ပေးတာပါ
    $employeeNumber = 'EMP-' . date('Y') . '-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);

    User::create([
        'employee_number' => $request->employee_number,
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
        'password' => Hash::make($request->password),
        // Force null if admin, even if data was somehow sent
        'department_id' => $request->role === 'admin' ? null : $request->department_id,
        
    ]);

    return redirect()->route('users.index')->with('success', '新規ユーザーを登録しました。');
}
    public function edit(User $user)
    {
        $departments = Department::all();
        return view('admin.users.edit', compact('user', 'departments'));
    }

   public function update(Request $request, User $user)
{
    $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'email', 'unique:users,email,' . $user->id],
        'role' => ['required', 'in:admin,manager,employee'],
        'department_id' => [
            'nullable',
            'exclude_if:role,admin',
            'exists:departments,id'
        ],
        // Only validate password if the field is not empty
        'password' => ['nullable', 'confirmed', 'min:8'], 
    ]);

    $data = [
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
        'department_id' => $request->role === 'admin' ? null : $request->department_id,
    ];

    // Only update password if user actually typed one in
    if ($request->filled('password')) {
        $data['password'] = Hash::make($request->password);
    }

    $user->update($data);

    return redirect()->route('users.index')->with('success', 'ユーザー情報を更新しました。');
}

    public function destroy(User $user)
    {
        $user->delete();
       return back()->with('success', 'ユーザーを削除しました。');
    }
}