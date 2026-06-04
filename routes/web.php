<?php

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Socialite;
use App\Models\User;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\Publications\ManuscriptController;
use App\Http\Controllers\Publications\Translator\TranslatorController;
use App\Http\Controllers\Publications\ReaderController;
use App\Http\Controllers\Publications\ManageController;


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

    // publication related routes --------------------------------------------------
    // Manuscript 
    Route::name('publications.manuscripts.')->prefix('/publications/manuscripts')->group(function () {
        Route::get('/', [ManuscriptController::class, 'index'])->name('index');
        Route::post('/update-message', [ManuscriptController::class, 'updateMessage']);
        Route::post('/update-notice', [ManuscriptController::class, 'updateNotice']);
        
        Route::get('/new', [ManuscriptController::class, 'createManuscript'])->name('createManuscript');
        Route::post('/store', [ManuscriptController::class, 'storeManuscript'])->name('storeManuscript');
        Route::get('/edit/{id}', [ManuscriptController::class, 'editManuscript'])->name('editManuscript');
        Route::get('/delete/{id}', [ManuscriptController::class, 'destroyManuscript'])->name('destroyManuscript');
        Route::get('/new-item/{menuscriptId}', [ManuscriptController::class, 'createItem'])->name('createItem');
        Route::post('/store-item', [ManuscriptController::class, 'storeItem'])->name('storeItem');
        Route::get('/edit-item/{id}', [ManuscriptController::class, 'editItem'])->name('editItem');
        Route::get('/delete-item/{id}', [ManuscriptController::class, 'destroyItem'])->name('destroyItem');
    })->middleware(['role:administrator|executive|translator|pab']);

    // Translator
    Route::name('publications.translator.')->prefix('/publications/translator')->group(function () {
            Route::get('publications/translator/{book}/{year}/{issue}', 
                        [TranslatorController::class, 'translator'])->name("publications.translator");
            Route::get('publications/translator/{book}/{year}/{issue}/{lang}/{s_lang}', 
                    [TranslatorController::class, 'parallelTrans']);
            Route::get('publications/translator/{book}/{year}/{issue}/{lang}/{s_lang}/dumpdata', 
                    [TranslatorController::class, 'dumpData']);
            Route::post('publications/translator/contents/{book}/{year}/{issue}/{lang}/{s_lang}', 
                    [TranslatorController::class, 'getTranslationContents']);
            Route::post('publications/translator/save', 
                    [TranslatorController::class, 'save']);
            Route::post('publications/translator/edit-property', 
                    [TranslatorController::class, 'editProperty']);
            Route::post('publications/translator/edit-book-name', 
                    [TranslatorController::class, 'editBookName']);
    })->middleware(['role:administrator|executive|translator|pab']);

    // Management (update from source, rebuild from XML)
    Route::group(['middleware' => ['role:administrator']], function () {
        // database
        Route::get('publications/manage/update-pub-db', [ManageController::class, 'updatePubDb'])->name('publications.manage.update-pub-db');
        Route::get('publications/manage/rebuild-pub-db', [ManageController::class, 'rebuildPubDb'])->name('publications.manage.rebuild-pub-db');
        Route::get('publications/manage/rebuild-sb-db', [ManageController::class, 'rebuildSbDb'])->name('publications.manage.rebuild-sb-db');

        // Authoring
        // Route::get('publications/composer/{book}/{lang}/{year}/{issue}', [ReaderController::class, 'composer'])->name('publications.reader.composer');
    });

});


Route::name('publications.reader.')->prefix('/publications/reader')->group(function () {
    Route::get('/', [ReaderController::class, 'index'])->name('index');
    Route::get('/bible-versions/{lang}', [ReaderController::class, 'bibleVersions'])->name('bibleVersions');
    Route::get('/book-lang/{book}/{year}/{issue}', [ReaderController::class, 'bookLanguages'])->name('bookLanguages');
    Route::get('/show/{book}/{lang}/{year}/{issue}', [ReaderController::class, 'show'])->name('show');
    Route::get('/xml/{book}/{lang}/{year}/{issue}', [ReaderController::class, 'xml'])->name('xml');
});
