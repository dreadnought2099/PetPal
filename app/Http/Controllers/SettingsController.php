<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index() {
        
        $user = Auth::user();
        return view('pages.settings.index', compact('user'));
    }
}
