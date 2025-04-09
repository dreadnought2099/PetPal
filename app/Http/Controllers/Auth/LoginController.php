<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Error message
        if (!Auth::attempt($validated)) {
           return back()->with('error', 'Invalid email or password');           
        }

        $user = Auth::user();

        Mail::to($user->email)->queue(new WelcomeMail($user));

        // success message
        return redirect()->route('pets.index')->with('success', "Login successful! Welcome, {$user->name}.");
    }

  

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
