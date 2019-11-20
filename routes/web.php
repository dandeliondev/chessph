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

//Route::get('/', function () {
//    return view('welcome');
//});
//
//Route::get('/', function () {
//    return redirect('rating/ncfp');
//});
Route::get('/', 'RatingController@index');
Route::get('/rating/ncfp', 'RatingController@index');
Route::get('/rating/ncfp/{cat}', 'RatingController@index');
Route::get('/ncfp/rating/{cat}', 'RatingController@index');
Route::get('/ncfp/rating/', 'RatingController@index');

Route::get('/ncfp/top100/{cat}/{age}/{gender}', 'RatingController@index');
Route::get('/ncfp/top100/{cat}/{age}', 'RatingController@index');
Route::get('/ncfp/top100/{cat}', 'RatingController@index');
Route::get('/sitemap.xml', 'SitemapController@index');

//Route::get('/import', 'RatingController@store_ratings');