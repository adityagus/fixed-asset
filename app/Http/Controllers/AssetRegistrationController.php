<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\LogRequestNumber;
use App\Models\AssetRegistration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class AssetRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        //
    }

    
    public function detail($ar_number){
      $assetRegistration = AssetRegistration::with('assetRegistrationItems', 'assetRegistrationApprovals')->where('ar_number', $ar_number)->first();

      return response()->json([
          'success' => true,
          'data' => $assetRegistration
      ]);
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function create(): JsonResponse
    {
        $latestAR = LogRequestNumber::createRequest('AR');
        $ar_number = $latestAR->request_number;
        // dd($ar_number);
        $status = 'inactive';
        $registered_by = auth()->user()->name ?? 'Unknown';
        $res = AssetRegistration::create([
          'ar_number' => $ar_number,
          'purchase_order_item_id' => null,
          'registered_by' => $registered_by,
          'registration_date' => now(),
          'status' => $status,
        ]);


        return response()->json([
            'success' => true,
            'data' => [
                'ar_number' => $ar_number,
                'status' => $status,
                'registered_by' => $registered_by,
            ],
            'message' => 'Create Asset Registration'
        ]);
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
}
