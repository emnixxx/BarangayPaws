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
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
{
    $request->validate([
        'user_name' => ['required', 'string', 'max:255'],
        'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
        'password' => ['required', 'confirmed', Rules\Password::defaults()],
        'gender' => ['required', 'in:Male,Female'],
        'contact_num' => ['required', 'string', 'max:20'],
        'address' => ['required', 'string', 'max:255'],
    ]);

    $user = User::create([
        'user_name' => $request->user_name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'gender' => $request->gender,
        'contact_num' => $request->contact_num,
        'address' => $request->address,
        'role' => 'resident',
        'status' => 'pending',
        'is_active' => true,
        'date_registered' => now(),
    ]);

    event(new Registered($user));
    Auth::login($user);

    return redirect(route('profile', absolute: false));
}
}
