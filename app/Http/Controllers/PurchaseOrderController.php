<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrderItem;
use App\Models\PurchaseRequest;
use Exception;
use Tymon\JWTAuth\JWTAuth;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use Illuminate\Http\Response;
use App\Models\LogRequestNumber;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;

class PurchaseOrderController extends Controller
{
  protected $jwtAuth;

  public function __construct(JWTAuth $jwtAuth)
  {
    $this->jwtAuth = $jwtAuth;
  }

  /**
   * Display a listing of the resource.
   */
  public function index(): Response
  {
    //
  }

  public function detail($po_number)
  {
    $purchaseOrder = PurchaseOrder::with('purchaseOrderItems.itemMaster.category', 'purchaseOrderItems.itemMaster.brand', 'purchaseOrderItems.itemMaster.type', 'approvals', 'purchaseRequest')->where('po_number', $po_number)->first();

    return response()->json([
      'success' => true,
      'data' => $purchaseOrder
    ]);
  }

  public function create(Request $request): JsonResponse
  {
    $getUser = $this->jwtAuth->parseToken()->getPayload()->get('user');
    // jwt auth user
    // $user = $request->get('jwt_user');

    $latestPo = LogRequestNumber::createRequest('PO');
    $po_number = $latestPo->request_number;
    $status = 'draft';
    $created_by = $getUser['username'] ?? 'Unknown';
    $res = PurchaseOrder::create([
      'po_number' => $po_number,
      'purchase_request_number' => null,
      'vendor_id' => null,
      'total_amount' => 0,
      'status' => $status,
      'created_by' => $created_by,
    ]);
    return response()->json([
      'success' => true,
      'data' => [
        'po_number' => $po_number,
        'result' => $res,
        'status' => $status,
        'created_by' => $created_by,
      ],
      'type' => 'purchase-order',
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
    // dd($request->all());
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

  public function submit(Request $request): JsonResponse
  {
    try {
        
        // $getUser = (object) $this->jwtAuth->parseToken()->getPayload()->get('user');
        // // $user = (object) $payload->get('user');
        // if($getUser->username != $request->get('requestedBy')){
        //     return response()->json([
        //       'success' => false,
        //       'message' => 'You cannot submit your own request.'
        //     ], 403);
        // }
        
      // jalankan fungsion saveDraftItem dulu untuk menyimpan data item dan total_amount
      $po_number = $request->input('formNumber');
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
    //   dd($request->all());
      $validated = $request->validate([
        'purchase_request_number' => 'required|string|max:255',
        'vendor_id' => 'required|exists:mst_vendor,id',
        'po_date' => 'required|date',
        'total_amount' => 'required|numeric|min:0',
        'status' => 'required|string|in:Draft,Waiting Approval,Approved,Rejected',
        'items' => 'required|array|min:1',
        // relasi ke master item
        'items.*.item_id' => 'required|numeric|min:1',
        'items.*.quantity' => 'required|integer|min:1',
        'items.*.unit_price' => 'required|numeric|min:0',
        'items.*.total_price' => 'required|numeric|min:0',
      ]);

      $purchaseOrder = PurchaseOrder::where('po_number', $po_number)->firstOrFail();
      $purchaseOrder->po_date = now();
      
      if (!$purchaseOrder) {
        return response()->json([
          'success' => false,
          'message' => 'Purchase Order not found'
        ], 404);
      }

      // Handle save/Update data
      $purchaseOrder->update($validated);

      if (PurchaseOrderItem::where('purchase_order_number', $purchaseOrder->po_number)->exists()) {
        PurchaseOrderItem::where('purchase_order_number', $purchaseOrder->po_number)->delete();
      }
      foreach ($request->items as $itemData) {
        $itemData['purchase_order_number'] = $purchaseOrder->po_number; // Set the purchase_order_number
        PurchaseOrderItem::create($itemData);
      }

      // create approval layers
      $approvalResponse = SubmissionController::CreateLayerApproval(new Request([
        'type' => 'purchase-order',
        'request_number' => $po_number,
        'cabang'=> null,
        'need_it_approval' => $purchaseOrder->purchaseRequest->jenis_permintaan == 'IT' ? 'true' : 'false',
      ]));

      // Kalau gagal, return error response dari CreateLayerApproval
        if ($approvalResponse instanceof JsonResponse) {
            $approvalData = $approvalResponse->getData(true); // as array
            if (isset($approvalData['success']) && !$approvalData['success']) {
                return $approvalResponse;
        }
    }
      

      self::updateStatusPO(new Request(['status' => 'waiting approval']), $po_number);


      return response()->json([
        'success' => true,
        'data' => $purchaseOrder
      ]);
      // update status to waiting approval
      // self::updateStatusPO(new Request(['status' => 'waiting approval']), $po_number);


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

  public static function updateStatusPO(Request $request, $po_number): JsonResponse
  {
    $request->validate([
      'status' => 'required|string|max:50'
    ]);


    $purchaseOrder = PurchaseOrder::where('po_number', $po_number)->first();

    if (!$purchaseOrder) {
      return response()->json([
        'success' => false,
        'message' => 'Purchase request not found.'
      ], 404);
    }

    $purchaseOrder->status = $request->status; // status sudah divalidasi string Inggris
    $purchaseOrder->save();


    return response()->json([
      'success' => true,
      'message' => 'Status updated successfully.',
      'data' => [
        'po_number' => $po_number,
        'status' => $purchaseOrder->status
      ]
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
