<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;    
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

class RegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        
        try {
            //  Extract the validated data
            $validated = $validator->validated();

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            // Should send email first before redirecting
            Mail::to($user->email)->queue(new WelcomeMail($user));

            // Ma login ang user after successfully registering
            Auth::login($user);

            $user->assignRole('Adopter');

            return redirect()->route('pets.index')->with('success', "Registration successful! Welcome, {$user->name}."); 
        } catch (\Exception $e) {

            Log::error('User registration failed successfully: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Registration failed. Please try again later.')->withInput();
        }
        
    } 
}
