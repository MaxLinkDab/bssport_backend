<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 0; $i<11; $i++){
        DB::table('products')->insert([
            'name' => 'Product '.$i,
            'description' => 'Описание продукта, все хорошо',
            'price' => rand(100,5000),
            'growth' => rand(50,100),
            'color' => 'white',
            'material' => 'cotton',
            'gender' => 'unisex',
            'kid'=> rand(0,1),
        ]);
    }
    }
}
