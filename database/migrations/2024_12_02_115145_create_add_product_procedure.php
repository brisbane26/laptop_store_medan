<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;


return new class extends Migration
{
    public function up(): void
    {
        // SQL untuk membuat prosedur tanpa menggunakan DELIMITER
        $sql = "
        DROP PROCEDURE IF EXISTS add_product_procedure;

        CREATE PROCEDURE add_product_procedure(
            IN product_name VARCHAR(255),
            IN category ENUM('new_laptop', 'second_laptop', 'others'),
            IN orientation VARCHAR(255),
            IN description TEXT,
            IN buy_price DECIMAL(15,2),
            IN sell_price DECIMAL(15,2),
            IN stock INT,
            IN discount DECIMAL(5,2),
            IN image VARCHAR(255),
            IN created_by INT
        )
        BEGIN
            -- Query untuk memasukkan produk ke dalam tabel produk
            INSERT INTO products (
                product_name, category, orientation, description, buy_price, 
                sell_price, stock, discount, image, created_by
            )
            VALUES (
                product_name, category, orientation, description, buy_price, 
                sell_price, stock, discount, image, created_by
            );
        END;
        ";

        // Menjalankan query untuk membuat prosedur
        DB::unprepared($sql);
    }

    public function down(): void
    {
        // Menghapus prosedur jika perlu rollback
        DB::unprepared("DROP PROCEDURE IF EXISTS add_product_procedure");
    }
};
