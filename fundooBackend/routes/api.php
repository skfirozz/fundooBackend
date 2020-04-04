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
Route::post('/createnote','NoteController@createNotes');
Route::post('/editnotes','NoteController@editNotes');
Route::post('/setColor','NoteController@setColor');
Route::post('/updateTrash','NoteController@trash');
Route::post('/nottrash','NoteController@notTrash');
Route::post('/archive','NoteController@archive');
Route::post('/updateArchive','NoteController@archive');
Route::post('/updatepin', 'NoteController@updatePin');
//------deleting permanently-----------
Route::post('/delete','NoteController@deleteNotes');

//------------DISPLAY------------------
Route::get('/getPinNotes','NoteController@getPinNotes');
Route::get('/getUnPinNotes','NoteController@getUnPinNotes');
Route::get('/getTrash','NoteController@getTrash');
Route::get('/getArchive','NoteController@getArchive'); 
Route::get('/getallLabels','NoteController@getallLabels');


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
