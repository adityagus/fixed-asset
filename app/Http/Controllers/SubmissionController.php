<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use App\Models\AssetRegistration;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Facades\JWTAuth;

class SubmissionController extends Controller
{
  
  public function index(Request $request, string $type): JsonResponse
    {
      try {
        // Ambil user dari JWT payload
        // $payload = JWTAuth::parseToken()->getPayload();
        // $user = $payload->get('user');
        
        switch ($type) {
            case 'pr':
                $model = PurchaseRequest::get();
                break;
            case 'po':
                $model = PurchaseOrder::get();
                break;
            case 'ar':
                $model = AssetRegistration::get();
                break;
            default:
                abort(404);
        }
        return response()->json([
            'success' => true,
            'data' => $model,
            // 'user' => $user // opsional, jika ingin kirim user ke frontend
        ]);
      } catch(\Exception $e) {
        return response()->json([
          'success' => false,
          'message' => $e->getMessage()
        ], 500);
      }
    }
  
  public function delete(Request $request, $type, $requestNumber): JsonResponse
  {

    switch ($type) {
      case 'pr':
        $title = 'Purchase Request';
        $submissionId = PurchaseRequest::where('pr_number', $requestNumber)->where('status', 'Draft')->firstOrFail()->id;
        $model = PurchaseRequest::findOrFail($submissionId);
        break;
      case 'po':
        $title = 'Purchase Order';
        $submissionId = PurchaseOrder::where('po_number', $requestNumber)->where('status', 'Draft')->firstOrFail()->id;
        $model = PurchaseOrder::findOrFail($submissionId);
        break;
      case 'ar':
        $title = 'Asset Registration';
        $submissionId = AssetRegistration::where('ar_number', $requestNumber)->where('status', 'Draft')->firstOrFail()->id;
        $model = AssetRegistration::findOrFail($submissionId);
        break;
      default:
        abort(404);
    }
    $model->delete();
    return response()->json([
      'success' => true,
      'message' => 'Deleted ' . $title . ' with Request Number ' . $requestNumber
    ]);
  }
}
