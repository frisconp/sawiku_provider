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

Route::get('/check', function () {
    return response('Server ready!');
});

Route::group(['prefix' => 'auth'], function () {
    Route::get('/detail', 'API\AuthController@detail')->middleware('auth:api');
    Route::post('/login', 'API\AuthController@login');
    Route::post('/register', 'API\AuthController@register');
    Route::get('/logout', 'API\AuthController@logout')->middleware('auth:api');
});

Route::group(['prefix' => 'admin'], function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('/login', 'API\Admin\AuthController@login');
        Route::get('/logout', 'API\Admin\AuthController@logout')->middleware('auth:api-admin');
        Route::get('/detail', 'API\Admin\AuthController@detail')->middleware('auth:api-admin');
    });
});

Route::group(['prefix' => 'article'], function () {
    Route::get('/', 'API\ArticleController@index');
    Route::get('/{id}', 'API\ArticleController@show');
    Route::post('/store', 'API\ArticleController@store')->middleware('auth:api-admin');
});

Route::group(['prefix' => 'menu'], function () {
    Route::get('/', 'API\MenuController@index');
    Route::get('/{menu}', 'API\MenuController@show');
    Route::post('/store', 'API\MenuController@store')->middleware('auth:api');
});

Route::group(['prefix' => 'order'], function () {
    Route::post('/store', 'API\OrderController@store')->middleware('auth:api');
    Route::get('/history', 'API\OrderController@getByUser')->middleware('auth:api');
    Route::post('/notification/handler', 'API\OrderController@notificationHandler');
    Route::post('/finish', function () {
        return response()->json(['message' => 'Transaksi selesai.']);
    });
});
