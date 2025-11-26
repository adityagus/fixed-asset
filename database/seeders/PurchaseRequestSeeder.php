<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PurchaseRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('purchase_requests')->insert([
            [
                'pr_number' => 'PR-2025001',
                'pr_date' => '2025-10-06',
                'created_by' => 'John Doe',
                'department' => 'Finance',
                'justification' => 'Purchase new laptops for staff.',
                'status' => 'draft',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pr_number' => 'PR-2025002',
                'pr_date' => '2025-10-07',
                'created_by' => 'Jane Smith',
                'department' => 'IT',
                'justification' => 'Upgrade server hardware.',
                'status' => 'waiting_approval',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pr_number' => 'PR-2025003',
                'pr_date' => '2025-10-08',
                'created_by' => 'Alice Brown',
                'department' => 'HR',
                'justification' => 'Office supplies restock.',
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pr_number' => 'PR-2025004',
                'pr_date' => '2025-10-09',
                'created_by' => 'Bob Martin',
                'department' => 'Logistics',
                'justification' => 'Purchase delivery van.',
                'status' => 'rejected',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pr_number' => 'PR-2025005',
                'pr_date' => '2025-10-10',
                'created_by' => 'Carol White',
                'department' => 'Marketing',
                'justification' => 'Promotional materials.',
                'status' => 'draft',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pr_number' => 'PR-2025006',
                'pr_date' => '2025-10-11',
                'created_by' => 'David Black',
                'department' => 'Sales',
                'justification' => 'Client gifts.',
                'status' => 'waiting_approval',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pr_number' => 'PR-2025007',
                'pr_date' => '2025-10-12',
                'created_by' => 'Eva Green',
                'department' => 'IT',
                'justification' => 'Software licenses renewal.',
                'status' => 'approved',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pr_number' => 'PR-2025008',
                'pr_date' => '2025-10-13',
                'created_by' => 'Frank Blue',
                'department' => 'Finance',
                'justification' => 'Accounting software upgrade.',
                'status' => 'rejected',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pr_number' => 'PR-2025009',
                'pr_date' => '2025-10-14',
                'created_by' => 'Grace Yellow',
                'department' => 'HR',
                'justification' => 'Employee training materials.',
                'status' => 'draft',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'pr_number' => 'PR-2025010',
                'pr_date' => '2025-10-15',
                'created_by' => 'Henry Purple',
                'department' => 'Logistics',
                'justification' => 'Warehouse equipment.',
                'status' => 'waiting_approval',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Contoh create asset registration dummy
        DB::table('asset_registrations')->insert([
            [
                'ar_number' => 'AR-2025001',
                'registration_date' => '2025-11-01',
                'po_number' => 'PO-2025001',
                'assetName' => 'Laptop Lenovo ThinkPad',
                'department' => 'Finance',
                'status' => 'Registered',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'ar_number' => 'AR-2025002',
                'registration_date' => '2025-11-02',
                'po_number' => 'PO-2025002',
                'assetName' => 'Server Dell PowerEdge',
                'department' => 'IT',
                'status' => 'Draft',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
