<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\ImportMasterController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/import-master', [ImportMasterController::class, 'showForm'])->name('import.master.form');
Route::post('/import-master', [ImportMasterController::class, 'import'])->name('import.master');
Route::get('/{any}', [AppController::class, 'index'])->where('any', '.*');
