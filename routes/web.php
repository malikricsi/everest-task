<?php

use App\Http\Controllers\RobotController;

use Illuminate\Support\Facades\Route;

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

Route::get('/', [RobotController::class, 'index'])->name('robot-index');
Route::get('/create', [RobotController::class, 'create'])->name('robot-create');
Route::post('/store', [RobotController::class, 'store'])->name('robot-store');
Route::get('/edit/{id}', [RobotController::class, 'edit'])->name('robot-edit');
Route::post('/update', [RobotController::class, 'update'])->name('robot-update');
Route::post('/delete', [RobotController::class, 'delete'])->name('robot-delete');

