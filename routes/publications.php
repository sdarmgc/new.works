<?php

/**
 * These routes require the user to be specific role
 */
// Manuscript 
Route::group(['middleware' => ['role:administrator|executive']], function () {
    Route::get('publications/manuscripts', 'Publications\ManuscriptController@index')->name('publications.manuscripts.index');
    Route::post('publications/manuscripts/update-message', 'Publications\ManuscriptController@updateMessage');
    Route::post('publications/manuscripts/update-notice', 'Publications\ManuscriptController@updateNotice');
    
    Route::get('publications/manuscripts/new', 'Publications\ManuscriptController@createManuscript')->name('publications.manuscripts.createManuscript');
    Route::post('publications/manuscripts/store', 'Publications\ManuscriptController@storeManuscript')->name('publications.manuscripts.storeManuscript');
    Route::get('publications/manuscripts/edit/{id}', 'Publications\ManuscriptController@editManuscript')->name('publications.manuscripts.editManuscript');
    Route::get('publications/manuscripts/delete/{id}', 'Publications\ManuscriptController@destroyManuscript')->name('publications.manuscripts.destroyManuscript');
    Route::get('publications/manuscripts/new-item/{menuscriptId}', 'Publications\ManuscriptController@createItem')->name('publications.manuscripts.createItem');
    Route::post('publications/manuscripts/store-item', 'Publications\ManuscriptController@storeItem')->name('publications.manuscripts.storeItem');
    Route::get('publications/manuscripts/edit-item/{id}', 'Publications\ManuscriptController@editItem')->name('publications.manuscripts.editItem');
    Route::get('publications/manuscripts/delete-item/{id}', 'Publications\ManuscriptController@destroyItem')->name('publications.manuscripts.destroyItem');

});

// Translator
Route::group(['middleware' => ['role:administrator|translator']], function () {
        Route::get('publications/translator/{book}/{year}/{issue}', 
                    'Publications\Translator\TranslatorController@translator')->name("publications.translator");
        Route::get('publications/translator/{book}/{year}/{issue}/{lang}/{s_lang}', 
                'Publications\Translator\TranslatorController@parallelTrans');
        Route::get('publications/translator/{book}/{year}/{issue}/{lang}/{s_lang}/dumpdata', 
                'Publications\Translator\TranslatorController@dumpData');
        Route::post('publications/translator/contents/{book}/{year}/{issue}/{lang}/{s_lang}', 
                'Publications\Translator\TranslatorController@getTranslationContents');
        Route::post('publications/translator/save', 
                'Publications\Translator\TranslatorController@save');
        Route::post('publications/translator/edit-property', 
                'Publications\Translator\TranslatorController@editProperty');
        Route::post('publications/translator/edit-book-name', 
                'Publications\Translator\TranslatorController@editBookName');
});

// Database management (update from source, rebuild from XML)
Route::group(['middleware' => ['role:administrator']], function () {
        Route::get('publications/manage/update-pub-db', 'Publications\ManageController@updatePubDb')->name('publications.manage.update-pub-db');
        Route::get('publications/manage/rebuild-pub-db', 'Publications\ManageController@rebuildPubDb')->name('publications.manage.rebuild-pub-db');
        Route::get('publications/manage/rebuild-sb-db', 'Publications\ManageController@rebuildSbDb')->name('publications.manage.rebuild-sb-db');
});


//Route::get('publications/reader/{book}/{lang}/{year}/{issue}', ReaderController::class);
Route::get('publications/reader', 'Publications\ReaderController@index')->name('publications.reader.index');
Route::get('publications/reader/bible-versions/{lang}', 'Publications\ReaderController@bibleVersions');
Route::get('publications/reader/book-lang/{book}/{year}/{issue}', 'Publications\ReaderController@bookLanguages');
Route::get('publications/reader/show/{book}/{lang}/{year}/{issue}', 'Publications\ReaderController@show')->name('publications.reader.show');
Route::get('publications/reader/xml/{book}/{lang}/{year}/{issue}', 'Publications\ReaderController@xml')->name('publications.reader.xml');
Route::get('publications/composer/{book}/{lang}/{year}/{issue}', 'Publications\ReaderController@composer')->name('publications.reader.composer');

/*
Route::get('publications/get-lib-data/content/_/{path1?}/{path2?}/{path3?}/{path4?}/{path5?}/{path6?}', 
            'Publications\Translator\TranslatorController@getLabraryData');
*/

