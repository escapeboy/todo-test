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

Route::get('/home', 'HomeController@index')->name('home');

Route::group([
    'prefix'    => 'lists',
    'namespace' => 'Tasks',
], function () {
    Route::get('/', 'ListsController@index')->name('lists');
    Route::post('/', 'ListsController@postForm')->name('lists');
    Route::get('/{item}', 'ListsController@getForm')->name('lists.form');
    Route::post('/{item}', 'ListsController@postForm');

    Route::get('/{item}/delete', 'ListsController@delete')->name('lists.delete');
    Route::get('/{item}/tasks', 'ListsController@getTasks')->name('lists.tasks');
    Route::post('/{item}/tasks', 'ListsController@createTask');
    Route::get('/export/{format}', 'ListsController@export')->name('lists.export');
});

Route::group([
    'prefix'    => 'tasks',
    'namespace' => 'Tasks',
], function () {
    Route::get('/', 'TasksController@index')->name('tasks');
    Route::post('/', 'TasksController@postForm');
    Route::get('/{item}', 'TasksController@getForm')->name('tasks.form');
    Route::post('/{item}', 'TasksController@postForm');
    Route::get('/{item}/delete', 'TasksController@delete')->name('tasks.delete');
    Route::get('/{item}/mark/{status}', 'TasksController@markTask')->name('tasks.mark');

    Route::get('/export/{format}', 'TasksController@export')->name('tasks.export');
});

Route::group([
    'prefix'     => 'users',
    'middleware' => ['role:admin'],
], function () {
    Route::get('/', 'UsersController@index')->name('users');
    Route::get('/{item}', 'UsersController@getForm')->name('users.form');
    Route::post('/{item}', 'UsersController@postForm');
    Route::get('/{item}/delete', 'UsersController@delete')->name('users.delete');
});