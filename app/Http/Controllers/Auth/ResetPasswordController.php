<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordController extends Controller
{

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with([
            'token' => $token, 
            'email' => $request->email
        ]);
    }

    public function reset(Request $request)
    {
        // Validate the reset data (token, email, password, etc.)
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Attempt to reset the password using the token
        $response = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                // Update the user's password
                $user->password = Hash::make($password);
                $user->save();

                // Fire the PasswordReset event
                event(new PasswordReset($user));
            }
        );

        // Redirect based on the result of the reset
        return $response == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', 'Your password has been successfully reset. You can now log in.')
            : back()->withErrors(['email' => 'Invalid token or email. Please try again.']);
    }
}
