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

Route::post('login', 'AuthController@login')->name('login');

Route::get('/login/{service}', 'SocialLoginController@redirect');
Route::get('/login/{service}/callback', 'SocialLoginController@callback');

Route::group([
    'middleware'    => ['auth:api']
], function () {
    Route::post('logout', 'AuthController@logout')->name('logout');
    Route::post('refresh', 'AuthController@refresh')->name('refresh');
    Route::get('profile', 'ProfileController@profile')->name('profile');
    Route::get('friends', 'ProfileController@friends')->name('friends');

    Route::apiResource('posts', 'PostsController');

    Route::group([
        'namespace' => 'Core',
        'prefix'    => 'core',
        'as'    => 'core.'
    ], function () {
        Route::apiResource('users', 'UsersController');
    });
});
