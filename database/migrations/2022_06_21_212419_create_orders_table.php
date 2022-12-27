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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id');
            $table->foreignId('baskets_id');
            $table->integer('sum');
            $table->string('status');
            $table->string('name');
            $table->string('surname');
            $table->string('patronymic');
            $table->string('email');
            $table->string('number_phone');
            $table->string('address');
            $table->string('tracker');
            $table->string('logistics_name');
            $table->string('name_organisation')->nullable();

/*             $table->foreignId('user_id');
            $table->foreignId('product_id');
            $table->integer('size');
            $table->integer('sum');
            $table->integer('amount')->default(1);
            $table->string('name');
            $table->string('surname');
            $table->string('patronymic');
            $table->string('email');
            $table->string('phone');
            $table->string('address');
            $table->string('name_organisation'); */
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
        Schema::dropIfExists('orders');
    }
};
