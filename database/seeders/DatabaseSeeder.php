<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Vendor;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use Illuminate\Database\Seeder;
use App\Models\AssetRegistration;
use App\Models\PurchaseOrderItem;
use App\Models\PurchaseRequestItem;
use App\Models\AssetRegistrationItem;
use App\Models\PurchaseOrderApproval;
use App\Models\PurchaseRequestApproval;
use App\Models\AssetRegistrationApproval;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Users
        User::factory(10)->create();
        
        // Create test user
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        
        // factory

        // Create Vendors
        $vendors = Vendor::factory(15)->create();

        // Create Purchase Requests with Items
        $purchaseRequests = collect();
        for ($i = 0; $i < 20; $i++) {
            $pr = PurchaseRequest::factory()->create();
            $purchaseRequests->push($pr);
            
            // Create 1-5 items per purchase request
            PurchaseRequestItem::factory()
                ->count(rand(1, 5))
                ->create(['purchase_request_id' => $pr->id]);
        }

        // Create Purchase Request Approvals for approved requests
        $approvedRequests = $purchaseRequests->where('status', 'approved');
        foreach ($approvedRequests as $pr) {
            PurchaseRequestApproval::factory()->create([
                'purchase_request_id' => $pr->id,
                'approval_status' => 'approved'
            ]);
        }

        // Create Purchase Orders for approved requests
        $purchaseOrders = collect();
        foreach ($approvedRequests as $pr) {
          $po = PurchaseOrder::factory()->create([
            'purchase_request_id' => $pr->id,
            'vendor_id' => $vendors->random()->id,
          ]);
          $purchaseOrders->push($po);

          // Create items for purchase orders based on request items
          foreach ($pr->purchaseRequestItems as $item) {
            PurchaseOrderItem::factory()->create([
              'purchase_order_id' => $po->id,
              'item_name' => $item->item_name,
              'quantity' => $item->quantity,
              'unit_price' => $item->estimate_unit_price,
              'total_price' => $item->total_price,
            ]);
          }
        }

        // Create Purchase Order Approvals
        foreach ($purchaseOrders as $po) {
            if (rand(1, 100) <= 80) { // 80% chance of having approval
                PurchaseOrderApproval::factory()->create([
                    'purchase_order_id' => $po->id,
                ]);
            }
        }

        // Create Asset Registrations for approved purchase order items
        $approvedPOs = $purchaseOrders->where('status', 'approved');
        foreach ($approvedPOs as $po) {
          foreach ($po->purchaseOrderItems as $item) {
              // Register 70% of items as assets
              if (rand(1, 100) <= 70) {
                  $asset = AssetRegistration::factory()->create([
                      'purchase_order_item_id' => $item->id,
                  ]);
                  
                AssetRegistrationItem::factory()
                ->count(rand(1, 3))
                ->create(['asset_registration_id' => $asset->id]);
                
                  // 60% chance of having approval
                  if (rand(1, 100) <= 60) {
                      AssetRegistrationApproval::factory()->create([
                          'asset_registration_id' => $asset->id,
                      ]);
                  }
              }
          }
      }
    }
}