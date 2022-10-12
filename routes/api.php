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

Route::group(
    [
        'middleware' => 'api',
        'namespace'  => 'App\Http\Controllers',
        'prefix'     => 'auth',
    ],
    function ($router) {

        Route::post('login', 'AuthController@login');
        Route::post('register', 'AuthController@register');
        Route::post('logout', 'AuthController@logout');
        Route::get('profile', 'AuthController@profile');
        Route::post('getuser', 'AuthController@getuserById');
        Route::post('refresh', 'AuthController@refresh');
        Route::post('deleteUser', 'AuthController@destroy');
        Route::post('updateUser', 'AuthController@update');

    }
);

Route::group(
    [
        'middleware' => 'api',
        'namespace'  => 'App\Http\Controllers',
    ],
    function ($router) {
        Route::get('allUsers', 'AuthController@allUsers');

    }
);

Route::group(
    [
        'middleware' => 'api',
        'namespace'  => 'App\Http\Controllers',
    ],
    function ($router) {
        Route::get('getCount', 'ProblemSolvingController@getCount');
        Route::get('inputString', 'ProblemSolvingController@inputString');
        Route::get('minSteps', 'ProblemSolvingController@minSteps');

    }
);
