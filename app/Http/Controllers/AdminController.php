<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\Adoption;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function dashboard()
    {

        $users = User::all();
        $roles = Role::all();
        $adoptions = Adoption::all();

        return view('pages.admin.dashboard', compact('users', 'roles', 'adoptions'));
    }

    public function deleteUser($id)
    {

        $user = User::findOrFail($id);

        // Log the user deletion action for auditing purposes
        Log::info("User with ID {$user->id} ({$user->name}) was deleted.");
        $user->delete();

        return back()->with('success', 'User deleted successfully');
    }

    public function changeRole(Request $request, $id)
    {
        // Check if the user has permission to change roles
        if (!Auth::user()->can('change role')) {
            abort(403, 'You are not authorized to change roles');
        }

        // Validate the incoming role
        $request->validate([
            'role' => 'required|exists:roles,name',
        ]);

        $user = User::findOrFail($id);

        // Detach all current roles and sync the new one
        try {
            // Get the role by name and pass its ID
            $role = Role::where('name', $request->role)->first();
            $user->roles()->sync([$role->id]);

            Log::info("User with ID {$user->id} ({$user->name}) role changed to {$request->role}.");

            if ($user->id === Auth::id()) {
                // Logout the current user
                auth()->logout();

                // Invalidate the session and regenerate the token
                request()->session()->invalidate();
                request()->session()->regenerateToken();

                // Redirect to login page with message
                return redirect()->route('login')->with('info', 'Your role has been updated. Please log in again.');
            }

            return back()->with('success', 'User role updated');
        } catch (\Exception $e) {
            // Log the error in case anything goes wrong
            Log::error("Error updating role for user with ID {$user->id}: " . $e->getMessage());
            return back()->with('error', 'There was an issue updating the user role');
        }
    }
}
