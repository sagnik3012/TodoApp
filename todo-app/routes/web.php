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

Route::post('task' , [TaskController::class , 'createTask']);
Route::get('task/{id}', [TaskController::class, 'getTaskById']);
Route::get('task', [TaskController::class, 'viewAllTasks']);
Route::patch('task/{id}', [TaskController::class, 'editTaskStatusById']);
Route::delete('task/{id}', [TaskController::class, 'deleteTaskById']);


