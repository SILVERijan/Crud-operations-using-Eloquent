<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Redirect based on user's role
            $user = Auth::user();
            
            if ($user->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->isReader()) {
                return redirect()->intended(route('reader.posts.index'));
            } elseif ($user->isCustomer()) {
                return redirect()->intended(route('customer.forms.index'));
            }

            // Fallback for users without roles
            return redirect()->intended(route('posts.index'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister() 
    {
        // Get only reader and customer roles (admin is created manually)
        $roles = \App\Models\Role::whereIn('slug', ['reader', 'customer'])->get();
        
        return view('auth.register', compact('roles'));
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign the selected role
        $user->roles()->attach($request->role_id);

        Auth::login($user);

        // Redirect based on role
        $role = \App\Models\Role::find($request->role_id);
        
        if ($role->slug === 'reader') {
            return redirect()->route('reader.posts.index');
        } elseif ($role->slug === 'customer') {
            return redirect()->route('customer.forms.index');
        }

        // Fallback
        return redirect()->route('posts.index');
    }

    public function logout(Request $request)
    {
        Auth::logout(); 

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
