<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

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

Route::get('menu', function() {
	return menu();
});

Route::get('menu-query', function(Request $request) {
	$param = ['lang' => $request->query('lang'), 'search' => $request->query('search')];
	return searchMenu($param);
});

Route::get('banner', function() {
	return banner();
});

Route::get('banner-query', function(Request $request) {
	$param = ['lang' => $request->query('lang'), 'search' => $request->query('search')];
	return searchBanner($param);
});

Route::get('banner/{btn_url}', function(string $param) {
	return findBanner($param);
});
