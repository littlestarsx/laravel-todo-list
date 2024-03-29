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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'middleware' => 'api',
    'namespace' => 'App\Http\Controllers',
], function ($router) {
    //auth
    Route::group(['prefix' => 'auth'], function ($router) {
        Route::post('login', 'JwtAuthController@login');
        Route::post('logout', 'JwtAuthController@logout');
        Route::post('refresh', 'JwtAuthController@refresh');
        Route::post('me', 'JwtAuthController@me');
    });
    //待办
    Route::group([
        'middleware' => 'auth.jwt',
        'prefix' => 'todo'
    ], function ($router) {
        Route::get('index', 'ToDoController@index');
        Route::get('show', 'ToDoController@show');
        Route::post('store', 'ToDoController@store');
        Route::post('update', 'ToDoController@update');
        Route::post('complete', 'ToDoController@changeComplete');
        Route::post('delete', 'ToDoController@delete');
    });
    //other
    Route::group(['prefix' => 'other'], function ($router) {
        Route::get('get_cookie', 'OtherController@getCookie');//获取cookie
        Route::get('test_package', 'TestPackageController@testPackage');//测试composer包
    });
});
