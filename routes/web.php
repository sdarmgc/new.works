<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Socialite;
use App\Models\User;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EmailController;


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


Route::group(['middleware' => ['role:administrator|executive']], function () {
    /*
    |--------------------------------------------------------------------------
    | Email Composer Routes
    |--------------------------------------------------------------------------
    */
    Route::prefix('email')->name('email.')->group(function () {
        Route::get('/compose',              [EmailController::class, 'compose'])->name('compose');
        Route::post('/send',                [EmailController::class, 'send'])->name('send');
        Route::get('/template/{name}',      [EmailController::class, 'loadTemplate'])->name('template');
        Route::get('/templates',            [EmailController::class, 'listTemplates'])->name('templates');
    });
});