<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\TodoController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::post('/refresh', [AuthController::class, 'refresh'])->name('refresh');


Route::controller(TodoController::class)->group(function () {
    Route::get('/todo_index', 'index')->name('todo_index');
    Route::post('/todo_store', 'store')->name('todo_store');
    Route::get('todo_show/{id}', 'show');
    Route::put('todo_update/{id}', 'update');
    Route::delete('todo_destroy/{id}', 'destroy');
});

// Route::get('/todo_index', [TodoController::class, 'index']);
// Route::post('/todo_store', [TodoController::class, 'store']);
// Route::get('/todo_show/{id}', [TodoController::class, 'show']);
// Route::post('/todo_update/{id}', [TodoController::class, 'update']);
// Route::delete('/todo_destroy/{id}', [TodoController::class, 'destroy']);
