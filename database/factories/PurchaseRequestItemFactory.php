<?php

namespace Database\Factories;

use App\Models\PurchaseRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PurchaseRequestItem>
 */
class PurchaseRequestItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 50);
        $unitPrice = fake()->randomFloat(2, 100, 10000);
        
        return [
            'purchase_request_number' => PurchaseRequest::factory(),
            'item_name' => fake()->randomElement([
                'Laptop Dell Inspiron 15',
                'HP Printer LaserJet',
                'Office Chair Ergonomic',
                'Monitor Samsung 24"',
                'Keyboard Mechanical',
                'Mouse Wireless',
                'Projector Epson',
                'Server Dell PowerEdge',
                'Network Switch 24-port',
                'UPS 1500VA',
                'Software License Office',
                'Antivirus License'
            ]),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_price' => $quantity * $unitPrice,
        ];
    }
}