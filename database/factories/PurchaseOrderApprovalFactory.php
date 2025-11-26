<?php

namespace Database\Factories;

use App\Models\PurchaseOrder;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseOrderApproval>
 */
class PurchaseOrderApprovalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'purchase_order_number' => PurchaseOrder::factory(),
            'approver_by' => fake()->name(),
            'approval_date' => fake()->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
            'approval_status' => fake()->randomElement(['waiting approval', 'approved', 'revised', 'rejected']),
        ];
    }
}