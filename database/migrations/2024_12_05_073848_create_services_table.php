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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('laptop_model')->nullable(false);
            $table->text('equipments')->nullable(); 
            $table->string('laptop_image')->nullable(); 
            $table->text('problem_description')->nullable(false);
            $table->decimal('price', 10, 2)->nullable();
            $table->enum('status', ['pending', 'approved', 'in-progress', 'ready-to-pickup', 'done', 'rejected'])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->date('order_date')->nullable(); 
            $table->date('end_date')->nullable(); 
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
        Schema::dropIfExists('services');
    }
};
