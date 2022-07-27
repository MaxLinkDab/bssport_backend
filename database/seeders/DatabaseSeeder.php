<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\SizeAndPrice;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        /* for($i=0; $i<=15; $i++){
        Product::factory()->create(
            [
            'name' => 'Test Product '.$i,
            'description' => 'Описание продукта № '.$i,
            'photo' => 'https://upload.wikimedia.org/wikipedia/ru/thumb/a/ac/No_image_available.svg/600px-No_image_available.svg.png',
            'price' => rand(100,5000),
            'price_kid' => rand(100,5000),
            'growth' => rand(50,100),
            'color' => 'white',
            'material' => 'cotton',
            'gender' => 'unisex',
            'kid'=> rand(0,1),
            ]
        );
        } */
        // Product::factory(10)->create();
        // SizeAndPrice::factory(10)->create();
        // Order::factory(5)->create();


    }
}
