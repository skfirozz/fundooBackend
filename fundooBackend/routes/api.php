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
Route::post('/updateNotes','NoteController@updateNotes');

Route::post('/setColor','NoteController@setColor');
Route::post('/updateTrash','NoteController@updateTrash');
Route::post('/updateArchive','NoteController@updateArchive');
Route::post('/updatepin', 'NoteController@updatePin');
Route::post('/addReminder', 'NoteController@addReminder');


//------deleting permanently-----------
Route::post('/deleteNotes','NoteController@deleteNotes');
Route::post('/deleteLabel','NoteController@deleteLabel');
Route::post('/deleteReminder','NoteController@deleteReminder');

//------------DISPLAY------------------
Route::get('/getAllNotes','NoteController@getAllNotes');
Route::get('/getPinNotes','NoteController@getPinNotes');
Route::get('/getUnPinNotes','NoteController@getUnPinNotes');
Route::get('/getTrashNotes','NoteController@getTrashNotes');
Route::get('/getArchive','NoteController@getArchiveNotes'); 
Route::get('/getallLabels','NoteController@getallLabels');

//----------Labels----------------
Route::post('/createLabel','NoteController@createLabel');
Route::get('/getLabels','NoteController@getUniqueLabels');
Route::get('/getLabelNotes','NoteController@getLabelNotes');

//--------userDetails----------
Route::get('/userDetails','ApiAuthController@userDetails');
Route::post('/addCollaborator','ApiAuthController@collaborator');
Route::post('/deleteCollaboration','NoteController@deleteCollaboration');

Route::post('/searchData','NoteController@searchData');

//----------PROFILE--------------------
Route::post('/updateProfile','ApiAuthController@updateProfile');


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
