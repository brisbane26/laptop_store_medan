<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $sql = "
        DROP PROCEDURE IF EXISTS add_product_procedure;

CREATE PROCEDURE add_product_procedure(
    IN product_name VARCHAR(255),
    IN category ENUM('new_laptop', 'second_laptop', 'others'),
    IN orientation TEXT,
    IN description TEXT,
    IN buy_price INT,
    IN sell_price INT,
    IN stock INT,
    IN discount INT,
    IN image VARCHAR(255)
)
BEGIN
    INSERT INTO products (product_name, category, orientation, description, buy_price, sell_price, stock, discount, image, created_at, updated_at)
    VALUES (product_name, category, orientation, description, buy_price, sell_price, stock, discount, image, NOW(), NOW());
END;
        ";

        DB::unprepared($sql);
    }

    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS add_product_procedure");
    }
};
