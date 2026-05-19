<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Socialite;
use App\Models\User;
use App\Http\Controllers\ContactController;


Route::get('/', function () {
    return view('welcome');
});

// Socialite Routes
Route::get('/login/{provider}/redirect', function ($provider) {
    return Socialite::driver($provider)->redirect();
});
 
Route::get('/login/{provider}/callback', function ($provider) {
    $socialUser = Socialite::driver($provider)->user();

    $user = User::where('email', $socialUser['email'])
        ->whereHas('profile', fn ($query) => $query->where('active', true))->first();
 
    // Log the user in manually
    Auth::guard('web')->login($user);

    // Redirect to the dashboard
    return redirect()->intended('/dashboard');
});

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/user/profile', function () {
        return view('profile.show');
    })->name('profile.show');
});
