<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\PurchaseRequestApproval;
use Exception;
use Log;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\PurchaseRequest;
use App\Models\LogRequestNumber;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\PurchaseRequestItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class PurchaseRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function detail($pr_number)
    {

        $purchaseRequest = PurchaseRequest::with('purchaseRequestItems.itemMaster.category', 'purchaseRequestItems.itemMaster.brand', 'purchaseRequestItems.itemMaster.type', 'approvals')->where('pr_number', $pr_number)->first();
        
        return response()->json([
            'success' => true,
            'data' => $purchaseRequest,
        ]);
    }
    
    public function fileListDetail () {
      
      $form_number = request()->query('form_number');
      Attachment::where('form_number', $form_number)->get();
      
      
        return response()->json([
            'success' => true,
            'data' => 'file list detail'
         ]);
    }
    
    

    /**
     * Show the form for creating a new resource.
     */
    public function create(): JsonResponse
    {
      // get name by jwt token
      $payload = JWTAuth::parseToken()->getPayload();

      // Ambil semua data dalam bentuk array
      $user = (object) $payload->get('user');
      // $created_by = ;

        // 1. generate pr_number
        $latestPr = LogRequestNumber::createRequest('PR');
        $pr_number = $latestPr->request_number;

        // 2. set status to draft
        $status = 'draft';

        // 3. siapa yang request by (user yang sedang login)
        $created_by = $user->username ?? 'Unknown';
        $idgrup = $user->idgrup ?? 'Unknown';
        $jabatan = $user->jabatan ?? 'Unknown';
        $cabang = $user->cabang ? $user->cabang : '9999';
        $clusterManager = SubmissionController::approvalCM($cabang)->first()->username ?? null;

        $res = PurchaseRequest::create([
          'pr_number' => $pr_number,
          'status' => $status,
          'idgrup' => $idgrup,
          'cluster_manager' => $clusterManager,
          'jabatan' => $jabatan,
          'created_by' => $created_by,
          'department' => $idgrup,
          'cabang' => $cabang,
        ]);
        // 4. return json response
        return response()->json([
            'success' => true,
            'data' => [
                'pr_number' => $pr_number,
                'status' => $status,
                'created_by' => $created_by,
            ],
            'type' => 'purchase-request',
        ]);
    }

    
    /** 
    * save as draft For Purchase Request Item
    */
   public function saveDraft(Request $request){
    // Validasi array items
          try {
        
         $request->validate([
            'pr_number' => 'required|exists:purchase_requests,pr_number',
            'jenis_permintaan' => 'required|string|max:255',
            'justification' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'nullable|numeric|min:0', // opsional
            'items.*.total_price' => 'required|numeric|min:0',
        ]);
        
        /**
         *  Get purchase request by pr_number
         * Update justification
         * Set status to 'submitted'
         * Save items (reuse saveDraftItem method)
         * */

        $requestModel = PurchaseRequest::where('pr_number', $request->pr_number)->first();
        // Handle file upload
        $requestModel->save();
        // Save items
        if(PurchaseRequestItem::where('purchase_request_number', $requestModel->pr_number)->exists()) {
            PurchaseRequestItem::where('purchase_request_number', $requestModel->pr_number)->delete();
        }
        foreach ($request->items as $itemData) {
            $itemData['purchase_request_number'] = $requestModel->pr_number; // Set the purchase_request_number
            $itemData['is_locked'] = false; // Set is_locked to false for draft items
            PurchaseRequestItem::create($itemData);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Purchase request saved successfully.'
        ]);
    }catch(ValidationException $e){
      return response()->json([
        'success' => false,
        'errors' => $e->errors()
      ], 422);
    }
    
    

   }
    
    
    /**
     * Store a newly created resource in storage.
     */
public function submit(Request $request): JsonResponse
{
    try {
        $payload = JWTAuth::parseToken()->getPayload();
        $user = (object) $payload->get('user');
        if ($user == $request->created_by) {
            return response()->json([
                'success' => false,
                'message' => 'You cannot submit your own request.'
            ], 403);
        }

        $validated = $request->validate([
            'pr_number' => 'required|exists:purchase_requests,pr_number',
            'jenis_permintaan' => 'required|string|max:255',
            'justification' => 'nullable|string',
            'cabang' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|numeric|min:1',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.pengajuan' => 'required|string|max:255',
            'items.*.unit_price' => 'nullable|numeric|min:0',
            'items.*.total_price' => 'required|numeric|min:0',
        ]);

        // Ambil purchase request
        $requestModel = PurchaseRequest::where('pr_number', $request->pr_number)->firstOrFail();
        $requestModel->pr_date = now();
        // Update fields
        $requestModel->update($validated);

        // Hapus dan simpan ulang items
        PurchaseRequestItem::where('purchase_request_number', $requestModel->pr_number)->delete();
        foreach ($request->items as $itemData) {
            $itemData['purchase_request_number'] = $requestModel->pr_number;
            $itemData['is_locked'] = false;
            
            PurchaseRequestItem::create($itemData);
        }

        // Create approval layers — tangkap response-nya!
        $approvalResponse = SubmissionController::CreateLayerApproval(new Request([
            'type' => 'purchase-request',
            'request_number' => $requestModel->pr_number,
            'idgrup' => $requestModel->idgrup,
            // pastikan need_it_approval berupa 'true'/'false' string, sesuai validasi method
            'cabang' => $requestModel->cabang,
            'need_it_approval' => $requestModel->jenis_permintaan === 'IT' ? 'true' : 'false',
        ]));
        // Kalau gagal, return error response dari CreateLayerApproval
        if ($approvalResponse instanceof \Illuminate\Http\JsonResponse) {
            $approvalData = $approvalResponse->getData(true); // as array
            if (isset($approvalData['success']) && !$approvalData['success']) {
                return $approvalResponse;
            }
        }

        // Update status PR kalau perlu
        self::updateStatusPR(new Request(['status' => 'waiting approval']), $requestModel->pr_number);

        return response()->json([
            'success' => true,
            'message' => 'Purchase request submitted successfully.'
        ]);
    } catch (ValidationException $e) {
        // validasi gagal
        return response()->json([
            'success' => false,
            'errors' => $e->errors()
        ], 422);
    } catch (Exception $e) {
        // error umum
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
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
     * Update status of a purchase request.
     */
    public static function updateStatusPR(Request $request, $pr_number): JsonResponse
    {
        $request->validate([
            'status' => 'required|string|max:50'
        ]);

        $purchaseRequest = PurchaseRequest::where('pr_number', $pr_number)->first();

        if (!$purchaseRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Purchase request not found.'
            ], 404);
        }

        $purchaseRequest->status = $request->status; // status sudah divalidasi string Inggris
        $purchaseRequest->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully.',
            'data' => [
                'pr_number' => $pr_number,
                'status' => $purchaseRequest->status
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        //
    }
}
