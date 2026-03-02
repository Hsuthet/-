<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Department;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    // public function create(): View
    // {
    //     return view('auth.register');
    // }
public function create()
{
    $departments = Department::all(); // get all departments
    return view('auth.register', compact('departments'));
}
    /**
     * Handle an incoming registration request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
    'name' => ['required', 'string', 'max:255'],
    'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
    'password' => ['required', 'confirmed', Rules\Password::defaults()],
    'department_id' => ['required', 'integer', 'exists:departments,id'], // Ensure the ID exists in your DB
]);

        // Create user with default role and department
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'REQUESTER',        // default role
            'department_id' => $request->department_id,           // default department (General)
        ]);

        // Fire registered event
        event(new Registered($user));

        // Login the user
        Auth::login($user);

        return redirect(route('dashboard'));
    }
}