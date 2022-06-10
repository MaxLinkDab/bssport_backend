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
            $table->char('name',100);
            $table->string('description')->nullable();
            $table->string('photo')->nullable();
            $table->integer('price');
            $table->integer('growth');
            $table->char('color', 40);
            $table->char('material',50)->nullable();
            $table->char('gender', 40)->nullable();
            $table->boolean('kid')->default(0);
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
