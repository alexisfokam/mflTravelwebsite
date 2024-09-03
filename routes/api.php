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
Route::middleware('auth:api')->group(function () {
    Route::get('/user', [App\Http\Controllers\API\AuthController::class, 'user']);
    Route::post('logout', [App\Http\Controllers\API\AuthController::class, 'logout']);
});
Route::controller(App\Http\Controllers\API\AuthController::class)->group(function () {
    
Route::post('register','registerFrom');
Route::post('login', 'login');
Route::post('refresh','refresh');
});
Route::controller(App\Http\Controllers\PaysController::class)->group(function() {
    Route::get('allPays', 'index');
    Route::post('storePays','store');
    Route::get('showPays/{pays}','show');
    Route::patch('updatePays/{pays}','updatePays');
    Route::delete('deletePays/{pays}','destroy');
});
    
Route::group(['middleware' => ['auth:api', 'check.user.type:1']], function () {
    
});

// Route::group(['middleware' => ['auth:api', 'check.user.type:0']], function () {
//     Route::get('/user-dashboard', 'UserController@dashboard');
// });
// Route::controller(AuthController::class)->group(function () {
//     Route::post('login', 'login');
//     Route::post('register', 'register');
//     Route::post('logout', 'logout');
//     Route::post('refresh', 'refresh');
// });