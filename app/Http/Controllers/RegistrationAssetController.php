<?php

namespace App\Http\Controllers;

use App\Models\Assets;
use App\Models\RegistrationAsset;
use App\Models\RegistrationAssetItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\LogRequestNumber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class RegistrationAssetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        //
    }


    public function detail($ra_number)
    {
        $assetRegistration = RegistrationAsset::with('registrationAssetItems.itemMaster.category', 'registrationAssetItems.itemMaster.brand', 'registrationAssetItems.itemMaster.type', 'approvals', 'purchaseOrder', 'purchaseOrder.purchaseRequest', 'purchaseOrder.purchaseOrderItems.itemMaster.category', 'purchaseOrder.purchaseOrderItems.itemMaster.brand', 'purchaseOrder.purchaseOrderItems.itemMaster.type')->where('ra_number', $ra_number)->first();

        return response()->json([
            'success' => true,
            'data' => $assetRegistration
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): JsonResponse
    {
        // get name by jwt token
        $payload = JWTAuth::parseToken()->getPayload();
        $poNumber = $request->get('purchase_order_number');
        
        // Ambil semua data dalam bentuk array
        $user = (object) $payload->get('user');
        $latestAR = LogRequestNumber::createRequest('RA');
        $ra_number = $latestAR->request_number;
        $status = 'Draft';
        $created_by = $user->username ?? 'Unknown';
        $res = RegistrationAsset::create([
            'ra_number' => $ra_number,
            'purchase_order_number' => null,
            'created_by' => $created_by,
            'status' => $status,
        ]);


        return response()->json([
            'success' => true,
            'data' => [
                'ra_number' => $ra_number,
                'res' => $res,
                'status' => $status,
                'created_by' => $created_by,
            ],
            'type' => 'registration-asset',
            'message' => 'Create Asset Registration'
        ]);
    }

    public function listApprovedRA(Request $request): JsonResponse
    {
        try {
            // Ambil semua Registration Asset yang statusnya Full Approved
            $registrationAssets = RegistrationAsset::with(
                'assetRegistrationItems',
                'assetRegistrationApprovals:id,request'
            )
                ->where('status', 'Full Approved')
                ->orderBy('created_at', 'desc')
                ->get();

            // Ambil semua nomor RA yang sudah ada di PO dengan status bukan draft
            $usedRaNumbers = \App\Models\PurchaseOrder::where('status', '!=', 'draft')
                ->whereNotNull('ra_number')
                ->pluck('ra_number')
                ->toArray();

            // Filter RA yang belum pernah dipakai di PO non-draft
            $filteredAssets = $registrationAssets->filter(function ($ra) use ($usedRaNumbers) {
                return !in_array($ra->ra_number, $usedRaNumbers);
            })->values();

            return response()->json([
                'success' => true,
                'data' => $filteredAssets
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        //
    }


    public function submit(Request $request): JsonResponse
    {
        try {
            $payload = JWTAuth::parseToken()->getPayload();
            $user = (object) $payload->get('user');
            $username = $user->username ?? 'Unknown';
            
            
            $ra_number = $request->input('ra_number');

            if (!$ra_number) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nomor RA wajib diisi'
                ], 400);
            }

            // Hitung total amount dari item
            $request->merge(['total_amount' => 0]);
            if (isset($request->items) && is_array($request->items)) {
                foreach ($request->items as $item) {
                    $request->merge(['total_amount' => $request->input('total_amount') + ($item['quantity'] * $item['unit_price'])]);
                }
            }

            // Validasi input dari user
            $validated = $request->validate([
                'ra_number' => 'required|string|max:255',
                'purchase_order_number' => 'required|string|max:255',
                'invoice_date' => 'nullable|date',
                'ra_date' => 'nullable|date',
                'items' => 'required|array|min:1',
                'items.*.item_id' => 'required|numeric|max:255',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.pengajuan' => 'required|string|max:255',
                'items.*.unit_price' => 'nullable|numeric|min:0', // opsional
                'items.*.total_price' => 'required|numeric|min:0',
            ]);

            // Cari data RegistrationAsset berdasarkan nomor RA
            $registrationAsset = RegistrationAsset::with('purchaseOrder', 'purchaseOrder.purchaseRequest')->where('ra_number', $ra_number)->firstOrFail();
            // dd($registrationAsset->purchaseOrder->purchaseRequest->jenis_permintaan);

            if (!$registrationAsset) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data Registration Asset tidak ditemukan'
                ], 404);
            }

            // Simpan invoice_date dan ra_date ke tabel registration_assets
            // Jika tidak ada, gunakan nilai sebelumnya (tidak diubah)
            $registrationAsset->update($validated);

            // Hapus item lama jika ada
            RegistrationAssetItem::where('registration_asset_number', $ra_number)->delete();

            // Simpan item baru ke registration_asset_items dan ke tabel aset
            foreach ($request->items as $item) {
                $regAssetItem = RegistrationAssetItem::create([
                    'registration_asset_number' => $ra_number,
                    'item_id' => $item['item_id'],
                    'unit_price' => $item['unit_price'] ?? 0,
                    'total_price' => $item['total_price'] ?? 0,
                    'quantity' => $item['quantity'],
                    'is_asset' => $item['is_asset'],
                    'pengajuan' => $item['pengajuan'],
                    // tambahkan field lain jika ada di tabel
                ]);


                // Siapkan data asset
                $assetData = [
                    'regist_item_id' => $regAssetItem->id,
                    'item_id' => $item['item_id'] ?? null,
                    'purchase_date' => $item['purchase_date'] ?? now(),
                    'purchase_price' => $item['unit_price'] ?? 0,
                    'location' => $request->cabang,
                    'is_asset' => $item['is_asset'] ?? 0,
                    'status' => 'inactive',
                    'assigned_to' => $request->requestedBy,
                    'department' => $request->department ?? null,
                    'serial_number' => $item['serial_number'] ?? null,
                    'vendor_id' => $request->vendor_id ?? null,
                    'warranty_until' => $item['warranty_until'] ?? null,
                    'notes' => $item['notes'] ?? null,
                    'registration_asset_item_id' => $regAssetItem->id,
                    'registration_asset_number' => $ra_number,
                    'purchase_request_number' => $request->prReference,
                    'ra_date' => $request->ra_date ?? null,
                    'purchase_order_number' => $request->poReference,
                    'quantity' => 1,
                ];

                // Cek duplikasi asset_number sebelum insert
                if (!empty($assetData['asset_number'])) {
                    $exists = Assets::where('asset_number', $assetData['asset_number'])->exists();
                    if ($exists) {
                        // Lewati insert jika asset_number sudah ada
                        continue;
                    }
                }

                // Insert ke tabel Assets
                Assets::create($assetData);
            }

            // Proses approval
            $approvalResponse = SubmissionController::CreateLayerApproval(new Request([
                'type' => 'registration-asset',
                'request_number' => $ra_number,
                'cabang' => null,
                'need_it_approval' => $registrationAsset->purchaseOrder->purchaseRequest->jenis_permintaan == 'IT' ? 'true' : 'false',
            ]));

            // Kalau gagal, return error response dari CreateLayerApproval
            if ($approvalResponse instanceof JsonResponse) {
                $approvalData = $approvalResponse->getData(true); // as array
                if (isset($approvalData['success']) && !$approvalData['success']) {
                    return $approvalResponse;
                }
            }

            // Update status RA menjadi "waiting approval"
            self::updateStatusRA(new Request(['status' => 'waiting approval']), $ra_number);


            return response()->json([
                'success' => true,
                'data' => $registrationAsset
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->validator->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public static function updateStatusRA(Request $request, $ra_number): JsonResponse
    {
        $request->validate([
            'status' => 'required|string|max:50'
        ]);


        $registrationAsset = RegistrationAsset::where('ra_number', $ra_number)->first();

        if (!$registrationAsset) {
            return response()->json([
                'success' => false,
                'message' => 'Purchase request not found.'
            ], 404);
        }

        $registrationAsset->status = $request->status; // status sudah divalidasi string Inggris
        $registrationAsset->save();


        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'data' => [
                'ra_number' => $ra_number,
                'status' => $registrationAsset->status
            ]
        ]);
    }


}
