<?php

namespace Database\Seeders;

use App\Models\PurchaseRequest;
use App\Models\PurchaseRequestItem;
use Illuminate\Database\Seeder;

class PurchaseRequestItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all purchase requests
        $purchaseRequests = PurchaseRequest::all();
        
        foreach ($purchaseRequests as $pr) {
            // Create 1-5 items per purchase request
            PurchaseRequestItem::factory()
                ->count(rand(1, 5))
                ->create(['purchase_request_id' => $pr->id]);
        }
    }
}