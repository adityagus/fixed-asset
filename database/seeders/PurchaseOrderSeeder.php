<?php

namespace Database\Seeders;

use App\Models\PurchaseRequest;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class PurchaseOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get approved purchase requests
        $approvedRequests = PurchaseRequest::where('status', 'approved')->get();
        $vendors = Vendor::all();
        
        foreach ($approvedRequests as $pr) {
            $po = PurchaseOrder::factory()->create([
                'purchase_request_id' => $pr->id,
                'vendor_id' => $vendors->random()->id,
            ]);
            
            // Create items for purchase orders based on request items
            $requestItems = $pr->purchaseRequestItems ?? [];
            foreach ($requestItems as $item) {
                PurchaseOrderItem::factory()->create([
                    'purchase_order_id' => $po->id,
                    'item_name' => $item->item_name,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->unit_price,
                    'total_price' => $item->total_price,
                ]);
            }
        }
    }
}