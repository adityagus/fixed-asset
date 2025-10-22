<?php

namespace Database\Factories;

use App\Models\AssetRegistration;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AssetRegistrationApproval>
 */
class AssetRegistrationApprovalFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'asset_registration_id' => AssetRegistration::factory(),
            'approver_by' => fake()->name(),
            'approved_at' => fake()->optional()->dateTimeBetween('-2 months', 'now'),
            'approval_status' => fake()->randomElement(['waiting_approval', 'approved', 'revised', 'rejected']),
        ];
    }
}