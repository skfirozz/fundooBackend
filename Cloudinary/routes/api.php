<?php

use Illuminate\Http\Request;


Route::post('/insertTest','TestORM@insertTest');
Route::post('/read','TestORM@read');
Route::post('/find','TestORM@find');
Route::post('/count','TestORM@count');
Route::post('/readMax','TestORM@readMax');
Route::post('/update','TestORM@update');
Route::post('/delete','TestORM@delete');
Route::post('/destroy','TestORM@destroy');
Route::post('/withTrash','TestORM@withTrash');

//--------------FUNDOO--------------------------
// Route::get('/test', 'ApiAuthController@test');

Route::post('/register', 'ApiAuthController@register');
Route::get('/verifyMail/{token}', 'ApiAuthController@verifyMail');
Route::post('/forgotPassword', 'ApiAuthController@forgotPassword');
Route::post('/resetPassword/{token}', 'ApiAuthController@resetPassword');

// Route::post('/login', 'ApiAuthController@login');
Route::post('/convertJwtToId/{token}', 'ApiAuthController@convertJwtToId');


Route::post('login', 'AuthController@login');
Route::post('logout', 'AuthController@logout');
Route::post('refresh', 'AuthController@refresh');
Route::post('me', 'AuthController@me');

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


//--------------------------------------
Route::post('logi', 'AuthController@login');


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
