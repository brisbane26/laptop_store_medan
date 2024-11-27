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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();                       // Primary key
            $table->foreignId('user_id')        // Relasi ke tabel users
                  ->constrained()
                  ->onDelete('cascade');
            $table->foreignId('product_id')     // Relasi ke tabel products
                  ->constrained()
                  ->onDelete('cascade');
            $table->integer('quantity')->default(1);  // Jumlah barang
            $table->timestamps();               // created_at & updated_at
        });
    }    

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
};