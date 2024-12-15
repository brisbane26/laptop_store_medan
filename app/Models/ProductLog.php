<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductLog extends Model
{
    use HasFactory;

    public function up()
{
    Schema::create('product_logs', function (Blueprint $table) {
        $table->id();
        $table->string('action');
        $table->text('old_value');
        $table->text('new_value');
        $table->string('admin_name');
        $table->timestamp('date')->useCurrent();
        $table->timestamps();
    });
}

}
