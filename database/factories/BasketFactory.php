<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Basket>
 */
class BasketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => 5,
            'product_id' => $this->faker->numberBetween(1,10),
            'vendor_code' => $this->faker->word(),
            'size'=> $this->faker->numberBetween(100,200),
            'sum'=> $this->faker->numberBetween(1000,3000),
            'amount'=> $this->faker->numberBetween(1,3),
        ];
    }
}
