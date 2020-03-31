<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::get('/test', 'ApiAuthController@test');

Route::post('/register', 'ApiAuthController@register');
Route::get('/verifyMail/{token}', 'ApiAuthController@verifyMail');
Route::post('/forgotPassword', 'ApiAuthController@forgotPassword');
Route::post('/resetPassword/{token}', 'ApiAuthController@resetPassword');

Route::post('/login', 'ApiAuthController@login');


//----------------Notes--------------------
Route::post('/notes','NoteController@createNotes');
Route::post('/editnotes','NoteController@editNotes');
Route::post('/setColor','NoteController@setColor');
Route::post('/trash','NoteController@trash');
Route::post('/nottrash','NoteController@notTrash');
Route::post('/archive','NoteController@archive');
Route::post('/unarchive','NoteController@unarchive');
//------deleting permanently-----------
Route::post('/deleteNotes','NoteController@deleteNotes');

//------------DISPLAY------------------
Route::post('/displayNotes','NoteController@displayNotes');
Route::post('/displayTrash','NoteController@displayTrash');
Route::post('/displayArchive','NoteController@displayArchive'); 


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
