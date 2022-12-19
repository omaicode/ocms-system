<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Modules\Core\Http\Middleware\EnsureAppInstalled;

Route::prefix('installer')->as('system.install.')->middleware(EnsureAppInstalled::class)->group(function() {
    Route::get('/', 'InstallController@index')->name('index');
    Route::post('/', 'InstallController@setup')->name('setup');
});
