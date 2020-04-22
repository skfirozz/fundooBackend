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

Route::get('/', 'ImageUploadController@home');

Route::post('/upload/images', [
  'uses'   =>  'ImageUploadController@uploadImages',
  'as'     =>  'uploadImage'
]);

// Route::get('/', function () {
//     return view('welcome');
// });

