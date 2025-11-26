<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Vendor>
 */
class VendorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama' => fake()->company(),
            'alamat' => fake()->address(),
            'kota' => fake()->city(),
            'telp1' => fake()->phoneNumber(),
            'telp2' => fake()->phoneNumber(),
            'pic' => fake()->name(),
            'nm_bank' => fake()->company(),
            'no_rek' => fake()->bankAccountNumber(),  
            'status' => fake()->boolean(80), // 80% chance of being true
        ];
    }
}