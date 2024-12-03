<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $sql = "
        DROP PROCEDURE IF EXISTS approve_order_procedure;

        CREATE PROCEDURE approve_order_procedure(
            IN order_id CHAR(36),
            IN note_id INT
        )
        BEGIN
            -- Update the order status to approved (1) and reset refusal_reason
            UPDATE orders
            SET status_id = 1,
                refusal_reason = NULL,
                note_id = note_id
            WHERE id = order_id;
        END;
        ";

        DB::unprepared($sql);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS approve_order_procedure");
    }
};
