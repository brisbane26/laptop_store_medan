<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $sql = "CREATE OR REPLACE VIEW product_view AS
            SELECT
                id AS product_id,
                product_name,
                stock,
                price,
                discount,
                image,
                orientation,
                description
            FROM products";
        
        // Jalankan query untuk membuat view
        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop view jika migrasi dibatalkan
        DB::statement("DROP VIEW IF EXISTS product_view");
    }
};
