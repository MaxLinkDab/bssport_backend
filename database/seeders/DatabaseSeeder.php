<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
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
     /*    for($i=0; $i<=15; $i++){
        Product::factory()->create(
            [
            'name' => 'Test Product '.$i,
            'description' => 'Описание продукта № '.$i,
            'price' => rand(100,5000),
            'photo' => 'https://upload.wikimedia.org/wikipedia/ru/thumb/a/ac/No_image_available.svg/600px-No_image_available.svg.png',
            'priceKid' => rand(100,5000),
            'growth' => rand(50,100),
            'color' => 'white',
            'material' => 'cotton',
            'gender' => 'unisex',
            'kid'=> rand(0,1),
            ]
        );
        } */
        Order::factory(5)->create();


    }
}
