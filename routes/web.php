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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();
Route::get('home', 'HomeController@index')->name('home');

Route::get('threads', 'ThreadController@index');
Route::post('threads', 'ThreadController@store')->middleware('must-be-confirmed');
Route::get('threads/create', 'ThreadController@create')->middleware('must-be-confirmed');
Route::get('threads/search', 'SearchController@show');
Route::get('threads/{channel}', 'ThreadController@index');
Route::get('threads/{channel}/{thread}', 'ThreadController@show');
Route::patch('threads/{channel}/{thread}', 'ThreadController@update');
Route::delete('threads/{channel}/{thread}', 'ThreadController@destroy');

Route::post('locked-threads/{thread}', 'LockedThreadController@store');
Route::delete('locked-threads/{thread}', 'LockedThreadController@destroy');

Route::patch('replies/{reply}', 'ReplyController@update');
Route::delete('replies/{reply}', 'ReplyController@destroy');

Route::post('replies/{reply}/best', 'BestReplyController@store')->name('best-replies.store');

Route::post('threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@store');
Route::delete('threads/{channel}/{thread}/subscriptions', 'ThreadSubscriptionController@destroy');

Route::get('threads/{channel}/{thread}/replies', 'ReplyController@index');
Route::post('threads/{channel}/{thread}/replies', 'ReplyController@store');

Route::post('replies/{reply}/favorites', 'FavoritesController@store');
Route::delete('replies/{reply}/favorites', 'FavoritesController@destroy');

Route::get('profiles/{user}', 'ProfileController@show');
Route::get('profiles/{user}/notifications', 'UserNotificationController@index');
Route::delete('profiles/{user}/notifications/{notification}', 'UserNotificationController@destroy');

Route::get('register/confirm', 'Auth\RegisterConfirmationController@index');

Route::post('users/{user}/avatar', 'UserAvatarController@store');
