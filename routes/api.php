<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'cat'], function() {
	Route::group(['prefix' => 'images'], function() {
		Route::get('', 'CatImagesController@index');
		Route::post('dispatch-fetch', 'CatImagesController@dispatchFetchCatImagesCategory');
	});

	Route::get('categories', 'CatImagesController@getCatCategories');
});

