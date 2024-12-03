<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $sql = "
        DROP PROCEDURE IF EXISTS reject_order_procedure;

        CREATE PROCEDURE reject_order_procedure(
            IN p_order_id CHAR(36),
            IN p_refusal_reason TEXT,
            IN p_user_id CHAR(36)
        )
        BEGIN
            DECLARE order_status INT;
            DECLARE order_coupon_used INT;

            -- Validasi refusal reason
            IF p_refusal_reason IS NULL OR p_refusal_reason = '' THEN
                SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'Refusal reason cannot be empty!';
            END IF;

            -- Ambil status order
            SELECT status_id, coupon_used
            INTO order_status, order_coupon_used
            FROM orders
            WHERE id = p_order_id;

            -- Validasi status order
            IF order_status = 4 THEN
                SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'Order status is already succeeded by admin';
            END IF;

            IF order_status = 5 THEN
                SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'Order status is already canceled by user';
            END IF;

            IF order_status = 3 THEN
                SIGNAL SQLSTATE '45000'
                SET MESSAGE_TEXT = 'Order status is already rejected';
            END IF;

            -- Update stok produk
            UPDATE products
            SET stock = stock + (
                SELECT quantity
                FROM order_details
                WHERE product_id = products.id
                AND order_id = p_order_id
            )
            WHERE id IN (
                SELECT product_id
                FROM order_details
                WHERE order_id = p_order_id
            );

            -- Update status order
            UPDATE orders
            SET status_id = 3,
                refusal_reason = p_refusal_reason
            WHERE id = p_order_id;

            -- Kembalikan kupon pengguna jika ada
            UPDATE users
            SET coupon = coupon + order_coupon_used
            WHERE id = p_user_id
            AND order_coupon_used > 0;
        END;
        ";

        DB::unprepared($sql);
    }

    public function down(): void
    {
        DB::unprepared("DROP PROCEDURE IF EXISTS reject_order_procedure");
    }
};
