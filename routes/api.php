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
// Route::resource('client', 'App\Http\Controllers\ClientController');
Route::prefix('v1')->middleware('jwt.auth')->group(function () {
    Route::post('me', 'App\Http\Controllers\AuthController@me');
    Route::apiResource('client', 'App\Http\Controllers\ClientController');
    Route::apiResource('brand', 'App\Http\Controllers\BrandController');
    Route::apiResource('car', 'App\Http\Controllers\CarController');
    Route::apiResource('location', 'App\Http\Controllers\LocationController');
    Route::apiResource('modelCar', 'App\Http\Controllers\ModelCarController');
});

Route::post('login', 'App\Http\Controllers\AuthController@login');
Route::post('logout', 'App\Http\Controllers\AuthController@logout');
Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');


//eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0Ojg5ODkvYXBpL2xvZ2luIiwiaWF0IjoxNjc5NzUwNjQzLCJleHAiOjE2Nzk3NTQyNDMsIm5iZiI6MTY3OTc1MDY0MywianRpIjoiRlI3Q3RrMmtxZmlnUEVTTiIsInN1YiI6IjEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3In0.Y5g476lHUtDqxzM25qHldmzKEiOFpUvGe8GmWvKnKN4