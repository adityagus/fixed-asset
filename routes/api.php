<?php

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\RegistrationAssetController;
use App\Http\Controllers\ReportController;
use Illuminate\Http\Request;
use App\Models\PurchaseRequest;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MasterController;
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
Route::post('/logout', [LoginController::class, 'logout']);

Route::get('/susutReport', [ReportController::class, 'susutReport']);

Route::middleware('jwt')->group(function () {

    Route::get('/user', [LoginController::class, 'getUser']);
    // purchase request
    Route::prefix('purchase-request/')->group(function () {
        Route::get('/index', [PurchaseRequestController::class, 'index']);
        Route::get('/detail/{pr_number}', [PurchaseRequestController::class, 'detail']);
        Route::post('/create', [PurchaseRequestController::class, 'create']);
        Route::post('/saveDraft', [PurchaseRequestController::class, 'saveDraft']);
        Route::post('/submit', [PurchaseRequestController::class, 'submit']);
        // upload attachment
    });

    // Upload File
    Route::get('/file-list', [AttachmentController::class, 'getFileList']);
    Route::post('/uploadFile', [AttachmentController::class, 'uploadFile']);
    Route::delete('/deleteFile', [AttachmentController::class, 'deleteFile']);
    
    // Notes
    Route::get('/notes', [SubmissionController::class, 'getNotes']);
    Route::post('/send-note', [SubmissionController::class, 'sendNote']);


    // approvals
    Route::post('/approvals/{type}', [SubmissionController::class, 'approvalsList']);
    Route::post('/set-approval', [SubmissionController::class, 'SetApprovalStatus']);
    Route::get('{formType}/list-approved', [SubmissionController::class, 'listApproved']);
    Route::post('/createApproval', [SubmissionController::class, 'CreateLayerApproval']);

    // purchase order
    Route::prefix('purchase-order/')->group(function () {
        Route::get('/list-approved-pr', [PurchaseOrderController::class, 'listApprovedPR']);
        Route::get('/detail/{po_number}', [PurchaseOrderController::class, 'detail']);
        Route::post('/create', [PurchaseOrderController::class, 'create']);
        Route::post('/saveDraftItem/{po_number}', [PurchaseOrderController::class, 'saveDraftItem']);
        Route::post('/submit', [PurchaseOrderController::class, 'submit']);
    });

    // registration asset
    Route::prefix('registration-asset/')->group(function () {
        // Route::get('/list-approved-po', [RegistrationAssetController::class, 'listApprovedRA']);
        Route::get('/detail/{ra_number}', [RegistrationAssetController::class, 'detail']);
        Route::post('/create', [RegistrationAssetController::class, 'create']);
        Route::post('/saveDraftItem/{ra_number}', [RegistrationAssetController::class, 'saveDraftItem']);
        Route::post('/submit', [RegistrationAssetController::class, 'submit']);
    });

    // submission
    Route::prefix('submission/')->group(function () {
        Route::get('{type}', [SubmissionController::class, 'index'])->middleware('jwt');
        Route::delete('delete/{type}/{id}', [SubmissionController::class, 'delete']);
        Route::put('edit/{type}/{id}', [SubmissionController::class, 'edit']);
        Route::get('/count-approval/{username}', [SubmissionController::class, 'getCountApproval']);
    });

    // prefix master
    Route::prefix('master/')->group(function () {
        Route::get('barang', [MasterController::class, 'getMasterBarang']);
        Route::post('barang', [MasterController::class, 'createMasterBarang']);
        Route::put('barang/{id}', [MasterController::class, 'updateMasterBarang']);
        Route::delete('barang/{id}', [MasterController::class, 'deleteMasterBarang']);
        Route::get('vendor', [MasterController::class, 'getVendorList']);
        Route::get('cabang', [MasterController::class, 'masterCabangChildren']);
        Route::get('area', [MasterController::class, 'masterAreaParents']);
        Route::get('merk', [MasterController::class, 'getMerkList']);
        Route::post('merk', [MasterController::class, 'createMerk']);
        Route::put('merk/{id}', [MasterController::class, 'updateMerk']);
        Route::delete('merk/{id}', [MasterController::class, 'deleteMerk']);
        Route::get('tipe-barang', [MasterController::class, 'getTipeBarangList']);
        Route::post('tipe-barang', [MasterController::class, 'createTipeBarang']);
        Route::put('tipe-barang/{id}', [MasterController::class, 'updateTipeBarang']);
        Route::delete('tipe-barang/{id}', [MasterController::class, 'deleteTipeBarang']);
        Route::get('kategori', [MasterController::class, 'getKategoriList']);
        Route::post('kategori', [MasterController::class, 'createKategori']);
        Route::put('kategori/{id}', [MasterController::class, 'updateKategori']);
        Route::delete('kategori/{id}', [MasterController::class, 'deleteKategori']);
        Route::post('vendor', [MasterController::class, 'createVendor']);
        Route::put('vendor/{id}', [MasterController::class, 'updateVendor']);
        Route::delete('vendor/{id}', [MasterController::class, 'deleteVendor']);
    });


    // prefix report
    Route::prefix('report/')->group(function () {
        Route::get('/asset', [ReportController::class, 'assetReport']);
        Route::get('/asset-depreciation', [ReportController::class, 'assetDepreciationReport']);
        Route::get('/barcode', action: [ReportController::class, 'reportBarcode']);
        Route::post('/asset/cabang', [ReportController::class, 'getCabangAssetReport']);
        // limit offset
        Route::post('/asset/paginated', [ReportController::class, 'assetReportPaginated']);
        
        Route::post('/asset/cabang/paginated', [ReportController::class, 'getCabangAssetReportPaginated']);
    });
    
    
    Route::prefix('statistik/')->group(function () {
        Route::get('/header-asset', [ReportController::class, 'headerAssetStatistik']);
        Route::get('/body-asset', [ReportController::class, 'bodyAssetStatistik']);
        Route::get('/asset-type', [ReportController::class, 'assetTypeStatistik']);
    });

});