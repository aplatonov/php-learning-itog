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

Route::post('register', 'Api\UserController@register');
Route::post('login', 'Api\UserController@login');

Route::middleware('auth:api')->group( function () {
    Route::get('users', 'Api\UserController@index');
    Route::get('user/{id}/confirm', 'Api\UserController@confirm');

    Route::resource('projects', 'Api\ProjectController');
    Route::get('projects/{id}/marks', 'Api\ProjectController@indexMarks');
    Route::put('projects/{id}/marks/{id_mark}/done', 'Api\ProjectController@setMarkIsDone');

    Route::get('techs', 'Api\TechnologiesController@index');
    Route::get('specs', 'Api\SpecialitiesController@index');
});