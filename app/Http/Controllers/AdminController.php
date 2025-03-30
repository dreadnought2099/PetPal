<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function dashboard() {
        return view('admin.dashboard');
    }

    public function deleteUser($id) {
        
        $user = User::findOrFail($id);
        $user->delete;

        return back()->with('success', 'User deleted successfully');
    }

    public function changeRole(Request $request, $id) {
        
        $user = User::findOrFail($id);

        $user->roles()->detach();
        $user->assignRole($request->role);

        return back()->with('success', 'User role updated');
    }

    public function deleteRequest($id) {

        $adoption = AdoptionRequest::findOrFail($id);
        $adoption->delete();

        return back()->with('success', 'Adoption request deleted');
    }
    
}
