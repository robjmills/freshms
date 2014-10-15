<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

Route::resource('users', 'UsersController');
Route::resource('organisations', 'OrganisationsController');
Route::resource('alerts', 'AlertsController');

Route::group(['prefix' => 'api/v1', 'before' => 'auth.token'], function() {
    Route::post('/notify', function () {
        return "foobar!";
    });
});