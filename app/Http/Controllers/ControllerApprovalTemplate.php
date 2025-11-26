<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

abstract class ControllerApprovalTemplate extends Controller
{
    // Set model di child class
    protected $model;
    protected $numberField = 'number'; // ex: pr_number, po_number, ar_number

    // List approval
    public function list(Request $request): JsonResponse
    {
        $data = $this->model::with('approvals')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    // Get approval detail
    public function detail($number): JsonResponse
    {
        $item = $this->model::with('approvals')
            ->where($this->numberField, $number)
            ->first();
        return response()->json([
            'success' => true,
            'data' => $item
        ]);
    }

    // Update approval status
    public function updateStatus(Request $request, $number): JsonResponse
    {
        $request->validate([
            'status' => 'required|string|max:50'
        ]);
        $item = $this->model::where($this->numberField, $number)->first();
        if (!$item) {
            return response()->json([
                'success' => false,
                'message' => 'Data not found.'
            ], 404);
        }
        $item->status = $request->status;
        $item->save();
        return response()->json([
            'success' => true,
            'message' => 'Status updated.',
            'data' => [
                $this->numberField => $number,
                'status' => $item->status
            ]
        ]);
    }

    // Tambah approval layer (override jika perlu)
    public function addLayer(Request $request, $number): JsonResponse
    {
        // Implementasi sesuai kebutuhan, misal simpan ke tabel approval_layers
        return response()->json([
            'success' => true,
            'message' => 'Approval layer added (implementasi di masing-masing controller).'
        ]);
    }
}
