<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {   
        $colorList = ['black', 'blue', 'white', 'red', 'yellow', 'green', 'purple', 'cyan'];
        $sexList = ['man','woman', 'unisex'];
        $materialList = ['wool', 'silk', 'clap', 'lenok', 'viscose', 'acetate', 'polyester', 'acrylic'];

        return [
        
                'name' => 'Product '.$this->faker->word(),
                'description' => 'Description product '.$this->faker->word(),
                'vendor_code' => $this->faker->word(),
                'photo' => 'https://upload.wikimedia.org/wikipedia/ru/thumb/a/ac/No_image_available.svg/600px-No_image_available.svg.png',
                'price_and_size' => $this->faker->numberBetween(1,10),
                'color' => $colorList[rand(0,count($colorList)-1)],
                'material' => $materialList[rand(0,count($materialList)-1)],
                'gender' => $sexList[rand(0,count($sexList)-1)],
                'kid'=> rand(0,1),
                
        ];
    }
}
