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
            $table->string('product_name', 255)->unique();
            $table->enum('category', ['new_laptop', 'second_laptop', 'others']);
            $table->text('orientation');
            $table->text('description');
            $table->integer('buy_price');
            $table->integer('sell_price');
            $table->integer('stock');
            $table->integer('discount');
            $table->string('image');
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
