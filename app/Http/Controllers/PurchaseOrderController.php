<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\JWTAuth;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use Illuminate\Http\Response;
use App\Models\LogRequestNumber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class PurchaseOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        //
    }

    public function detail($po_number)
    {
      $purchaseOrder = PurchaseOrder::with('purchaseOrderItems', 'purchaseApprovals')->where('po_number', $po_number)->first();

      return response()->json([
          'success' => true,
          'data' => $purchaseOrder
      ]);
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): JsonResponse
    {
      // jwt auth user
      $user = auth()->user();
      // $user = $request->get('jwt_user');
      dd($user);

      $latestPo = LogRequestNumber::createRequest('PO');
      $po_number = $latestPo->request_number;
      $status = 'draft';
      $requested_by = $user->name;
      $res = PurchaseOrder::create([
        'po_number' => $po_number,
        'purchase_request_id' => null,
        'vendor_id' => null,
        'total_amount' => 0,
        'status' => $status,
        'created_by' => $requested_by,
      ]);
      return response()->json([
          'success' => true,
          'data' => [
              'po_number' => $po_number,
              'result' => $res,
              'status' => $status,
              'requested_by' => $requested_by,
          ]
      ]);
        // generate po_number
        // set status to draft
        // siapa yang request by (user yang sedang login)
        // return jsnon response
        // ---
        
        
    }
    
    public function saveDraftItem(Request $request, $po_number): JsonResponse
    {
      $purchaseOrder = PurchaseOrder::where('po_number', $po_number)->first();
      
      
      // dd($request->all());
      if (!$purchaseOrder) {
        return response()->json([
          'success' => false,
          'message' => 'Purchase Order not found'
        ], 404);
      }
      
      // calculate total amount from items
      $total_amount = 0;
      if (isset($request->items) && is_array($request->items)) {
        foreach ($request->items as $item) {
          $total_amount += $item['quantity'] * $item['unit_price'];
        }
      }
      
      $request->merge(['total_amount' => $total_amount]);
      
      // update purchase order with request data
      $purchaseOrder->update($request->all());
      return response()->json([
        'success' => true,
        'data' => $purchaseOrder
      ]);
    }
    

    /**
     * Store a newly created resource in storage.
     */
    
    public function submit(Request $request): JsonResponse
    {
      try{
        // jalankan fungsion saveDraftItem dulu untuk menyimpan data item dan total_amount
        $po_number = $request->input('po_number');
        $this->saveDraftItem($request, $po_number);
        if (!$po_number) {
          return response()->json([
            'success' => false,
            'message' => 'PO Number is required'
          ], 400);
        }
        
        // calculate total amount from items
      $request->merge(['total_amount' => 0]);
      if (isset($request->items) && is_array($request->items)) {
        foreach ($request->items as $item) {
          $request->merge(['total_amount' => $request->input('total_amount') + ($item['quantity'] * $item['unit_price'])]);
        }
      }
        
        $validated = $request->validate([
          'purchase_request_id' => 'required|exists:purchase_requests,id',
          'vendor_id' => 'required|exists:mst_vendor,id',
          'total_amount' => 'required|numeric|min:0',
          'status' => 'required|string|in:draft,waiting_approval,approved,rejected',
          'items' => 'required|array|min:1',
          'items.*.item_name' => 'required|string|max:255',
          'items.*.quantity' => 'required|integer|min:1',
          'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $purchaseOrder = PurchaseOrder::where('po_number', $po_number)->first();
        if (!$purchaseOrder) {
          return response()->json([
            'success' => false,
            'message' => 'Purchase Order not found'
          ], 404);
        }

      }catch(\Illuminate\Validation\ValidationException $e){
        return response()->json([
          'success' => false,
          'message' => 'Validation failed',
          'errors' => $e->validator->errors()
        ], 422);
      }
      
      $purchaseOrder = PurchaseOrder::where('po_number', $po_number)->first();
      if (!$purchaseOrder) {
        return response()->json([
          'success' => false,
          'message' => 'Purchase Order not found'
        ], 404);
      }
      // validate required fields
      $validated = $request->validate([
        'purchase_request_id' => 'required|exists:purchase_requests,id',
        'vendor_id' => 'required|exists:mst_vendor,id',
        'total_amount' => 'required|numeric|min:0',
      ]);
      // update status to waiting_approval
      $purchaseOrder->update(array_merge($validated, ['status' => 'waiting_approval']));
      return response()->json([
        'success' => true,
        'data' => $purchaseOrder
      ]);
    }
    
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
}
