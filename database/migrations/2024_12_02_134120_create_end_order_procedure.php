<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $sql = "
        DROP PROCEDURE IF EXISTS end_order_procedure;

        CREATE PROCEDURE end_order_procedure(
            IN order_id CHAR(36),
            IN total_price DECIMAL(15,2)
        )
        BEGIN
            -- Update order to 'done'
            UPDATE orders
            SET status_id = 4,
                note_id = 5,
                is_done = 1,
                refusal_reason = NULL
            WHERE id = order_id;

            -- Insert transactional data
            INSERT INTO transactions (category_id, description, income, outcome, created_at, updated_at)
            VALUES (1, CONCAT('Sales of products in order #', order_id), total_price, NULL, NOW(), NOW());
        END;
        ";

        DB::unprepared($sql);
    }

    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS end_order_procedure");
    }
};
