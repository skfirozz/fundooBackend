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

Route::post('/insertTest','TestORM@insertTest');
Route::post('/read','TestORM@read');
Route::post('/find','TestORM@find');
Route::post('/count','TestORM@count');
Route::post('/readMax','TestORM@readMax');
Route::post('/update','TestORM@update');
Route::post('/delete','TestORM@delete');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
