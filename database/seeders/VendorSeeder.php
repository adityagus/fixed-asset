<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Vendor::factory(20)->create();
        
        // Create some specific vendors
        Vendor::factory()->create([
            'vendor_name' => 'PT. Teknologi Indonesia',
            'contact_person' => 'John Doe',
            'phone' => '021-12345678',
            'email' => 'contact@teknologi.id',
            'address' => 'Jakarta Selatan, DKI Jakarta'
        ]);
        
        Vendor::factory()->create([
            'vendor_name' => 'CV. Office Solutions',
            'contact_person' => 'Jane Smith',
            'phone' => '021-87654321',
            'email' => 'info@officesolutions.co.id',
            'address' => 'Tangerang, Banten'
        ]);
    }
}