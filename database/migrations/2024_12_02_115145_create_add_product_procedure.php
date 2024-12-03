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
            IN stock INT,
            IN price DECIMAL(10,2),
            IN discount DECIMAL(5,2),
            IN orientation TEXT,
            IN description TEXT,
            IN image VARCHAR(255)
        )
        BEGIN
            INSERT INTO products (product_name, stock, price, discount, orientation, description, image, created_at, updated_at)
            VALUES (product_name, stock, price, discount, orientation, description, image, NOW(), NOW());
        END;
        ";

        DB::unprepared($sql);
    }

    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS add_product_procedure");
    }
};
