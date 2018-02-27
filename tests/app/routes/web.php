<?php

use Illuminate\Support\Facades\Route;

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

Route::view('/', 'welcome', ['expectations' => require(__DIR__.'/../../expectations/translation.expectation.php')]);
Route::get('/lang/{locale}', function (string $locale) {
    session()->put(
        config('flipbox-sdk.modules.translation.session'),
        $locale
    );

    return redirect()->back();
});

Route::get('menu', function () {
    return menu();
});
