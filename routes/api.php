<?php

use Illuminate\Http\Request;
use App\Models\PurchaseRequest;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\PurchaseOrderController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\AssetRegistrationController;

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

Route::post('/login', [LoginController::class, 'login']);


Route::middleware('jwt')->group(function () {

  // purchase request
  Route::prefix('purchase-request/')->group(function () {
    Route::get('/index', [PurchaseRequestController::class, 'index']);
    Route::get('/detail/{pr_number}', [PurchaseRequestController::class, 'detail']);
    Route::get('/create', [PurchaseRequestController::class, 'create']);
    Route::post('/saveDraftItem/{pr_number}', [PurchaseRequestController::class, 'saveDraftItem']);
    Route::post('/submit', [PurchaseRequestController::class, 'submit']);
  });


  // purchase order
  Route::prefix('purchase-order/')->group(function () {
    Route::get('/detail/{po_number}', [PurchaseOrderController::class, 'detail']);
    Route::get('/create', [PurchaseOrderController::class, 'create']);
    Route::post('/saveDraftItem/{po_number}', [PurchaseOrderController::class, 'saveDraftItem']);
    Route::post('/submit', [PurchaseOrderController::class, 'submit']);
  });
  
  // asset registration
  Route::prefix('asset-registration/')->group(function () {
    Route::get('/detail/{asset_tag}', [AssetRegistrationController::class, 'detail']);
    Route::get('/create', [AssetRegistrationController::class, 'create']);
    Route::post('/saveDraftItem/{asset_tag}', [AssetRegistrationController::class, 'saveDraftItem']);
    Route::post('/submit', [AssetRegistrationController::class, 'submit']);
  });

  // submission
  Route::prefix('submission/')->group(function () {
    Route::get('{type}', [SubmissionController::class, 'index'])->middleware('jwt');
    Route::delete('delete/{type}/{id}', [SubmissionController::class, 'delete']);
  });

});