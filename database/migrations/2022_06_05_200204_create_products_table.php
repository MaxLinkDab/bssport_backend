<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->char('vendor_code',100)->unique();
            $table->char('name',100);
            $table->string('description')->nullable();
            $table->foreignId('photo')->nullable();
            $table->foreignId('price_and_size');
            $table->integer('sale')->default(0);
            $table->foreignId('color');
            $table->char('material',50)->nullable();
            $table->char('gender', 40)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
