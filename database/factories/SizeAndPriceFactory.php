<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\size_and_price>
 */
class SizeAndPriceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'product_id' => $this->faker->numberBetween(24,43),
            'size' => $this->faker->numberBetween(100,180),
            'price' => $this->faker->numberBetween(2000,3000),
        ];
    }
}
