<?php

use App\Http\Controllers\Controller;
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

Route::middleware('api')->group(function () {
    Route::post('/login',[App\Http\Controllers\API\AuthController::class,'login']);
    Route::post('/register',[App\Http\Controllers\API\AuthController::class,'register']);
});
Route::group(['middleware' => ['auth:api', 'check.user.type:1']], function () {
    Route::get('/admin-dashboard', 'AdminController@dashboard');
});

Route::group(['middleware' => ['auth:api', 'check.user.type:0']], function () {
    Route::get('/user-dashboard', 'UserController@dashboard');
});
// Route::controller(AuthController::class)->group(function () {
//     Route::post('login', 'login');
//     Route::post('register', 'register');
//     Route::post('logout', 'logout');
//     Route::post('refresh', 'refresh');
// });