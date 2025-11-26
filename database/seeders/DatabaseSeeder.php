<?php

namespace Database\Seeders;

use App\Models\Assets;
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
use App\Models\Asset;

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
                ->create(['purchase_request_number' => $pr->pr_number]);
        }

        // Create Purchase Request Approvals for approved requests
        $approvedRequests = $purchaseRequests->where('status', 'approved');
        foreach ($approvedRequests as $pr) {
            PurchaseRequestApproval::factory()->create([
                'purchase_request_number' => $pr->pr_number,
                'approval_status' => 'approved'
            ]);
        }

        // Create Purchase Orders for approved requests
        $purchaseOrders = collect();
        foreach ($approvedRequests as $pr) {
          $po = PurchaseOrder::factory()->create([
            'purchase_request_number' => $pr->pr_number,
            'vendor_id' => $vendors->random()->id,
            'created_by' => fake()->name(),
            'updated_by' => fake()->name(),
          ]);
          $purchaseOrders->push($po);

          // Create items for purchase orders based on request items
          foreach ($pr->purchaseRequestItems as $item) {
            PurchaseOrderItem::factory()->create([
              'purchase_order_number' => $po->po_number,
              'item_name' => $item->item_name,
              'quantity' => $item->quantity,
              'unit_price' => $item->unit_price,
              'total_price' => $item->total_price,
            ]);
          }
        }

        // Create Purchase Order Approvals
        foreach ($purchaseOrders as $po) {
            if (rand(1, 100) <= 80) { // 80% chance of having approval
                PurchaseOrderApproval::factory()->create([
                    'purchase_order_number' => $po->po_number,
                ]);
                // Tandai PO sebagai approved
                $po->status = 'approved';
                $po->save();
            }
        }

        // Create Asset Registrations for approved purchase orders
        $approvedPOs = $purchaseOrders->where('status', 'approved');
        foreach ($approvedPOs as $po) {
            // Contoh: buat 1-3 asset registration per PO
            $assetCount = rand(1, 3);
            for ($i = 0; $i < $assetCount; $i++) {
                $ra = AssetRegistration::factory()->create([
                    'ar_number' => 'RA-' . $po->po_number . '-' . ($i+1),
                    // 'purchase_order_number' => $po->po_number,
                    'registered_by' => fake()->name(),
                    'registration_date' => now()->subDays(rand(0, 30)),
                    'status' => ['active', 'inactive'][rand(0, 1)],
                ]);

                // Create asset(s) for each asset registration
                Assets::create([
                    'asset_number' => 'AST-' . $po->po_number . '-' . ($i+1),
                    'serial_number' => 'SN-' . fake()->unique()->numerify('#####'),
                    'purchase_order_number' => $po->po_number,
                    'register_assets_number' => $ra->ar_number,
                    'item_name' => 'Barang ' . ($i+1),
                    'category' => ['Elektronik', 'Furniture', 'Komputer'][rand(0,2)],
                    'status' => 'active',
                    'assigned_to' => null,
                    'location' => 'Lokasi ' . rand(1,10),
                    'purchase_date' => $po->created_at->format('Y-m-d'),
                    'purchase_price' => rand(1000000, 10000000),
                    'warranty_until' => now()->addYears(1)->format('Y-m-d'),
                    'notes' => 'Asset otomatis dari seeder.',
                ]);
            }
        }

    }
}