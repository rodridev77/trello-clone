<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StatusController;
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
    return redirect()->route('tasks.index');
});

Route::get('/home', function () {
    return redirect()->route('tasks.index');
})->name('home');

Auth::routes();

//Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => 'auth'], function() {
    Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('tasks/sync', [TaskController::class, 'sync'])->name('tasks.sync');
    Route::put('tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
});

Route::group(['middleware' => 'auth'], function() {
    Route::post('statuses', [StatusController::class, 'store'])->name('statuses.store');
    Route::put('statuses', [StatusController::class, 'update'])->name('statuses.update');
});