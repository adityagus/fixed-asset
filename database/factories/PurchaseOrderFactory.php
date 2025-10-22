<?php

namespace Database\Factories;

use App\Models\PurchaseRequest;
use App\Models\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseOrder>
 */
class PurchaseOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'po_number' => 'PO-' . fake()->year() . fake()->unique()->numberBetween(1000, 9999),
            'purchase_request_id' => PurchaseRequest::factory(),
            'vendor_id' => Vendor::factory(),
            'total_amount' => fake()->numberBetween(100000, 10000000),
            'status' => fake()->randomElement(['draft', 'waiting_approval', 'approved', 'revised', 'rejected']),
            'created_by' => fake()->name(),
            'updated_by' => fake()->name(),
        ];
    }

    public function approved()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'approved',
        ]);
    }
}