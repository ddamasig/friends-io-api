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
    'middleware'    => ['auth:sanctum']
], function () {
    Route::get('profile', 'AuthController@profile')->name('profile');

    Route::group([
        'namespace' => 'Core',
        'prefix'    => 'core',
        'as'    => 'core.'
    ], function () {
        Route::apiResource('users', 'UsersController');
    });

    Route::group([
        'namespace' => 'Library',
        'prefix'    => 'library',
        'as'    => 'library.'
    ], function () {
        // Route::apiResource('materials', 'MaterialController');
    });
});
