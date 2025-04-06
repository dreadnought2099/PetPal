<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdoptionController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\SettingsController;
use Illuminate\Auth\Notifications\ResetPassword;

Route::get('/', function () {
    return view('home');
})->name('home');

Auth::routes(['verify' => true]);

// Logout outside the verified middleware so user can still logout even the email is unverified
Route::middleware('auth')->post('/logout', [LoginController::class, 'logout'])->name('logout');

// Profile outisde the verified middleware so user can still access profile page even the email is unverified
Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');

Route::get('/pets', [PetController::class, 'index'])->name('pets.index');


Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('register', [RegistrationController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegistrationController::class, 'register']);

    // Shows the forgot password form
    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');

    // Handle sending reset password email
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');

    // Shows reset password form
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');

    // Handle password reset
    Route::post('reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

// For verifying the email registered
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', [VerificationController::class, 'notice'])->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->middleware('signed')->name('verification.verify');
    Route::post('/email/resend', [VerificationController::class, 'resend'])->middleware('throttle:6,1')->name('verification.resend');
});


Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');


    // Administrator
    Route::middleware(['role:Administrator'])->group(function () {
        Route::get('admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // User Management
        Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser'])->name('admin.deleteUser');
        Route::post('/admin/users/{id}/role', [AdminController::class, 'changeRole'])->name('admin.changeRole');
    });

    // Shelter & Administrator
    Route::middleware(['role:Shelter|Administrator'])->group(function () {

        Route::get('/pets/create', [PetController::class,  'create'])->name('pets.create');
        Route::post('/pets', [PetController::class, 'store'])->name('pets.store');
        Route::get('/pets/{id}/edit', [PetController::class, 'edit'])->name('pets.edit');
        Route::put('/pets/{id}', [PetController::class, 'update'])->name('pets.update');
        Route::delete('/pets/{pet}', [PetController::class, 'destroy'])->name('pets.destroy');

        // Adoption Request Management
        Route::patch('/adopt/{adoption}/approve', [AdoptionController::class, 'approve'])->name('adopt.approve');
        Route::patch('/adopt/{adoption}/reject', [AdoptionController::class, 'reject'])->name('adopt.reject');
        Route::get('/adopt/pending', [AdoptionController::class, 'pending'])->name('adopt.pending');
    });

    // Adopter
    Route::middleware(['role:Adopter'])->group(function () {

        Route::get('/adopt', [AdoptionController::class, 'index'])->name('adopt.index');
        Route::post('/adopt/request', [AdoptionController::class, 'store'])->name('adopt.store');
        Route::get('/adopt/log', [AdoptionController::class, 'adoptionLog'])->name('adopt.log');
        Route::get('/adopt/apply/{pet}', [AdoptionController::class, 'create'])->name('adopt.apply');
        Route::put('/adopt/{adoption}', [AdoptionController::class, 'update'])->name('adopt.update');
        Route::get('/adopt/{adoption}/edit', [AdoptionController::class, 'edit'])->name('adopt.edit');
        Route::delete('/adopt/{adoption}', [AdoptionController::class, 'destroy'])->name('adopt.destroy');
    });
});
