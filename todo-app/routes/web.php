<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::post('tasks/single' , [TaskController::class , 'createTask']);
Route::post('tasks', [TaskController::class, 'createTasks']);
Route::get('tasks/{id}', [TaskController::class, 'getTaskById']);
Route::get('tasks', [TaskController::class, 'viewAllTasks']);
Route::patch('tasks/{id}', [TaskController::class, 'editTaskStatus']);
Route::delete('tasks/{id}', [TaskController::class, 'deleteTask']);


