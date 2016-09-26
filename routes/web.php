<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'backup-table'], function() {
    Route::get('/', 'TableDownloadController@show');
    Route::post('/run', 'TableDownloadController@backup');
});

Route::group(['prefix' => 'import-table'], function() {
    Route::get('/', 'TableImportController@upload');
    Route::post('/run', 'TableImportController@run');
});