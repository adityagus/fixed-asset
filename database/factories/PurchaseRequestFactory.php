<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseRequest>
 */
class PurchaseRequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'pr_number' => 'PR-' . fake()->year() . fake()->unique()->numberBetween(1000, 9999),
            'pr_date' => fake()->dateTimeBetween('-6 months', 'now')->format('Y-m-d'),
            'created_by' => fake()->name(),
            'department' => fake()->randomElement(['IT', 'Finance', 'HR', 'Operations', 'Marketing', 'Sales', 'Logistics']),
            'justification' => fake()->sentence(10),
            'status' => fake()->randomElement(['draft', 'waiting approval', 'approved', 'rejected']),
        ];
    }
}