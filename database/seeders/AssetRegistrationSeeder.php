<?php

namespace Database\Seeders;

use App\Models\PurchaseOrder;
use App\Models\AssetRegistration;
use App\Models\AssetRegistrationApproval;
use Illuminate\Database\Seeder;

class AssetRegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get approved purchase orders
        $approvedPOs = PurchaseOrder::where('status', 'approved')->get();
        
        foreach ($approvedPOs as $po) {
            $poItems = $po->purchaseOrderItems ?? [];
            
            foreach ($poItems as $item) {
                // Register 80% of items as assets
                if (rand(1, 100) <= 80) {
                    $asset = AssetRegistration::factory()->create([
                        'purchase_order_item_id' => $item->id,
                    ]);
                    
                    // 70% chance of having approval
                    if (rand(1, 100) <= 70) {
                        AssetRegistrationApproval::factory()->create([
                            'asset_registration_id' => $asset->id,
                        ]);
                    }
                }
            }
        }
    }
}