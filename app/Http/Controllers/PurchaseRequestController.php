<?php

namespace App\Http\Controllers;

use App\Models\LogRequestNumber;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\PurchaseRequest;
use Illuminate\Http\JsonResponse;
use App\Models\PurchaseRequestItem;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class PurchaseRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function detail($pr_number)
    {

        $purchaseRequest = PurchaseRequest::with('purchaseRequestItems', 'approvals')->where('pr_number', $pr_number)->first();

        return response()->json([
            'success' => true,
            'data' => $purchaseRequest
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): JsonResponse
    {
        // 1. generate pr_number
        $latestPr = LogRequestNumber::createRequest('PR');
        $pr_number = $latestPr->request_number;

        // 2. set status to draft
        $status = 'draft';

        // 3. siapa yang request by (user yang sedang login)
        $requested_by = auth()->user()->name ?? 'Unknown';

        $res = PurchaseRequest::create([
          'pr_number' => $pr_number,
          'status' => $status,
          'requested_by' => $requested_by,
          'department' => 'IT',
        ]);
        // 4. return jsnon response
        return response()->json([
            'success' => true,
            'data' => [
                'pr_number' => $pr_number,
                'status' => $status,
                'requested_by' => $requested_by,
            ]
        ]);
    }

    
    /** 
    * save as draft For Purchase Request Item
    */
   public function saveDraftItem(Request $request, $pr_number){
    // Validasi array items
    try{
      $validated = $request->validate([
          'items' => 'required|array|min:1',
          'items.*.item_name' => 'required|string|max:255',
          'items.*.quantity' => 'required|integer|min:1',
          'items.*.estimate_unit_price' => 'nullable|numeric|min:0', // opsional
          'items.*.total_price' => 'required|numeric|min:0',
          // Tambahkan field lain sesuai kebutuhan
      ]);
    $prId = PurchaseRequest::where('pr_number', $pr_number)->first()->id;
      
    $savedItems = [];
    
    // purchase request item sudah ada maka dihapus dulu

    if(PurchaseRequestItem::where('purchase_request_id', $prId)->exists()) {
        PurchaseRequestItem::where('purchase_request_id', $prId)->delete();
    }


    foreach ($validated['items'] as $itemData) {
        $itemData['purchase_request_id'] = $prId; // Set the purchase_request_id
        $savedItems[] = PurchaseRequestItem::create($itemData);
    }

    return response()->json([
        'success' => true,
        'items' => $savedItems
    ]);
      
    }catch(\Illuminate\Validation\ValidationException $e){
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
         $request->validate([
            'pr_number' => 'required|exists:purchase_requests,pr_number',
            'justification' => 'required|string|max:1000',
            'url_file' => 'required|file|mimes:pdf|max:10240', // max 10MB | pdf only
            'items' => 'required|array|min:1',
            'items.*.item_name' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.estimate_unit_price' => 'nullable|numeric|min:0', // opsional
            'items.*.total_price' => 'required|numeric|min:0',
        ]);
        
        /**
         *  Get purchase request by pr_number
         * Update justification
         * Set status to 'submitted'
         * Save items (reuse saveDraftItem method)
         * */
        
        $requestModel = PurchaseRequest::where('pr_number', $request->pr_number)->first();
        $requestModel->justification = $request->justification;
        $requestModel->status = 'waiting_approval';
        // Handle file upload
        if ($request->hasFile('url_file')) {
            $file = $request->file('url_file');
            $filePath = $file->store('purchase_requests', 'public'); // Simpan di storage/app/public/purchase_requests
            $requestModel->url_file = $filePath;
        }
        $requestModel->save();
        // Save items
        if(PurchaseRequestItem::where('purchase_request_id', $requestModel->id)->exists()) {
            PurchaseRequestItem::where('purchase_request_id', $requestModel->id)->delete();
        }
        foreach ($request->items as $itemData) {
            $itemData['purchase_request_id'] = $requestModel->id; // Set the purchase_request_id
            PurchaseRequestItem::create($itemData);
        }

        return response()->json([
            'success' => true,
            'message' => 'Purchase request submitted successfully.'
        ]);
    }catch(ValidationException $e){
      return response()->json([
        'success' => false,
        'errors' => $e->errors()
      ], 422);
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): RedirectResponse
    {
        //
    }
}
