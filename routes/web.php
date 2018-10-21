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

Route::group(['prefix' => 'webapi'], function() {
    Route::get('/conversations', 'Api\ConversationController@index');
});

Route::get('/home', 'HomeController@index')->name('home');
Route::post('/get-friends', 'HomeController@getFriends')->name('get-friends');
Route::post('/session/create', 'SessionController@store')->name('session-create');
Route::post('/send/{session}', 'ChatController@send')->name('send-message');
Route::post('/session/{session}/chats', 'ChatController@chats')->name('chats');
Route::post('/session/{session}/read', 'ChatController@read')->name('session-read');