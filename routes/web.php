<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ItemController;

// Route::get('/', function () {
//     return view('welcome');
// });

// Route::get('/', [ItemController::class, 'index']);
// Route::post('/items', [ItemController::class, 'store']);
// Route::put('/items/{id}', [ItemController::class, 'update']);
// Route::delete('/items/{id}', [ItemController::class, 'destroy']);


use App\Http\Controllers\TaskController;

Route::get('/', [TaskController::class, 'index']);
Route::get('/tasks', [TaskController::class, 'tasks']);
Route::post('/tasks', [TaskController::class, 'store']);
Route::put('/tasks/{task}', [TaskController::class, 'update']);
Route::delete('/tasks/{task}', [TaskController::class, 'destroy']);
