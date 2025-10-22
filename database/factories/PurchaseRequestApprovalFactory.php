<?php

namespace Database\Factories;

use App\Models\PurchaseRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseRequestApproval>
 */
class PurchaseRequestApprovalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'purchase_request_id' => PurchaseRequest::factory(),
            'approver_by' => fake()->name(),
            'approval_date' => fake()->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
            'approval_status' => fake()->randomElement(['approved', 'rejected']),
            'remarks' => fake()->optional()->sentence(),
        ];
    }
}