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

//Route::resource('users', 'UserController');
Route::get('/users/editprofile', 'UserController@edit');
Route::get('/users/edit/{id}', 'UserController@edit');
Route::put('/users/update/{id}', 'UserController@update');

Route::get('/home', 'HomeController@index');

Route::get('/admin/users','AdminController@showUsers');
Route::delete('/admin/users/delete/{id}','AdminController@destroyUser');
Route::post('/admin/users/confirm/{id}','AdminController@confirmUser');
Route::post('/admin/users/block/{id}','AdminController@blockUser');
Route::post('/admin/users/role/{id}','AdminController@adminUser');
Route::delete('/admin/users/delete/{id}','AdminController@deleteUser');
Route::get('/register/confirm/{confirmationCode}','HomeController@unblockUserByMail');

Route::get('/personal','PersonalController@showPersonal')->name('personal');
Route::get('/userPersonal','PersonalController@showPersonal')->name('userPersonal'); 
Route::delete('/personal/delete/{id}','PersonalController@destroyPerson');
Route::post('/personal/info/{id}','PersonalController@showContactInfo');
Route::get('/personal/add','PersonalController@addPerson'); 
Route::post('/personal','PersonalController@storePerson'); 
Route::get('/personal/{id}/edit','PersonalController@edit');

Route::get('/projects','ProjectsController@showProjects')->name('projects');
Route::get('/userProjects','ProjectsController@showProjects')->name('userProjects'); 
Route::post('/projects/info/{id}','ProjectsController@showContactInfo');
Route::post('/projects/active/{id}','ProjectsController@confirmProject');
Route::get('/projects/add','ProjectsController@addProject'); 
Route::post('/projects','ProjectsController@storeProject'); 
Route::get('/projects/{id}/edit','ProjectsController@edit');
Route::delete('/projects/delete/{id}','ProjectsController@deleteProject');

Route::get('/companies','UserController@showCompanies')->name('companies');
Route::post('/companies/info/{id}','UserController@showCompanyInfo');

Route::get('/admin/comments','CommentsController@showComments');
Route::delete('/admin/comments/delete/{id}','CommentsController@destroyComment');
Route::get('/admin/comments/add','CommentsController@addComment');
Route::post('/admin/comments/block/{id}','CommentsController@blockComment');
Route::post('/admin/comments','CommentsController@storeComment');
Route::get('/admin/comments/{id}/edit','CommentsController@edit');
Route::get('/comments','CommentsController@showCommentsMain');
Route::get('/comments/add','CommentsController@addCommentMain');

Route::resource('/admin/specialities','SpecialitiesController');
Route::resource('/admin/technologies','TechnologiesController');
Route::resource('/admin/notecategories','NoteCategoriesController');

Route::get('/contacts','HomeController@getContacts');
Route::post('/contacts','HomeController@sendFeedback');

Route::get('/admin/notes','NotesController@showNotes')->name('notes');
Route::delete('/admin/notes/delete/{id}','NotesController@destroyNote');
Route::get('/userNotes','NotesController@showUserNotes')->name('userNotes');

Route::get('/admin/settings','SettingsController@edit')->name('settings');
Route::post('/admin/settings','SettingsController@store');
