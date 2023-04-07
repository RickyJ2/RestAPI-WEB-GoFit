<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('loginWeb', 'App\Http\Controllers\Api\AuthController@loginWeb');
Route::post('loginMobile', 'App\Http\Controllers\Api\AuthController@loginMobile');

Route::group(['middleware' => 'auth:sanctum'], function(){
    Route::post('logout', 'App\Http\Controllers\Api\AuthController@logout');
});
