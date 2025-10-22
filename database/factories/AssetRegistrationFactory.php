<?php

namespace Database\Factories;

use App\Models\PurchaseOrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AssetRegistration>
 */
class AssetRegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'purchase_order_item_id' => PurchaseOrderItem::factory(),
            'ar_number' => 'AST-' . fake()->year() . '-' . fake()->unique()->numberBetween(10000, 99999),
            'registered_by' => fake()->name(),
            'registration_date' => fake()->dateTimeBetween('-1 year', 'now')->format('Y-m-d'),
            'status' => fake()->randomElement(['active', 'inactive', 'disposed']),
        ];
    }

    public function active()
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }
}