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

use Illuminate\Support\Facades\Cache;

use App\CatImagePerCategory;

Route::get('/', function () {

	return view('welcome'); 
});

Route::get('test', function() {
	dd(
		Cache::get('hats_count'),
		Cache::get('space_count'),
		Cache::get('funny_count'),
		Cache::get('total_images_fetched')
	);
});