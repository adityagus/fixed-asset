<?php

namespace Database\Factories;

use App\Models\AssetRegistration;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AssetRegistrationItem>
 */
class AssetRegistrationItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 10);
        $unitPrice = fake()->randomFloat(2, 100, 5000);

        return [
            'asset_registration_number' => AssetRegistration::factory()->create()->ar_number,
            'item_name' => fake()->randomElement([
                'Monitor Samsung 24"',
                'Keyboard Mechanical',
                'Mouse Wireless',
                'Printer Epson',
                'Laptop Lenovo ThinkPad',
                'UPS 1500VA',
                'Projector BenQ',
                'Network Switch 24-port',
                'Office Chair',
                'Software License'
            ]),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => $quantity * $unitPrice,
        ];
    }
}