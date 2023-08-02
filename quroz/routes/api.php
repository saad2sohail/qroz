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

//To create a Task or Subtask
Route::post('/tasks', 'App\Http\Controllers\TodoController@create');

//To delete a Task(update deleted at)
Route::delete('/tasks/{id}', 'App\Http\Controllers\TodoController@delete');

//To mark a task and related sub tasks as Completed
Route::patch('/tasks/{id}/completed',  'App\Http\Controllers\TodoController@completeTask');

//To view pending tasks
Route::get('/tasks/pending',  'App\Http\Controllers\TodoController@viewPendingTasks');

Route::get('/tasks/{filter?}', 'App\Http\Controllers\TodoController@viewTasksByDueDate');

Route::get('/tasks/search/{filter?}', 'App\Http\Controllers\TodoController@searchTitle');



// Filter Tasks based on Due Date (Today, This Week, Next Week, Overdue)
Route::get('/tasks/filter/{type}', [TodoController::class, 'filter']);

// Search Tasks based on Title
Route::get('/tasks/search/{title}', [TodoController::class, 'search']);