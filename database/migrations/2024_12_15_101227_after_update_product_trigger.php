<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Trigger untuk UPDATE
        DB::unprepared('
CREATE TRIGGER after_update_product_trigger
AFTER UPDATE ON products
FOR EACH ROW
BEGIN
    -- Pastikan perubahan produk hanya dicatat jika dilakukan oleh admin (role_id = 1)
    IF NEW.updated_by IS NOT NULL AND 
       (SELECT role_id FROM users WHERE id = NEW.updated_by) = 1 THEN

        -- Set nilai untuk old_values dan new_values
        SET @old_values = CONCAT(
            "Name: ", OLD.product_name, ", Category: ", OLD.category, 
            ", Orientation: ", OLD.orientation, ", Description: ", OLD.description, 
            ", Buy Price: ", OLD.buy_price, ", Sell Price: ", OLD.sell_price, 
            ", Stock: ", OLD.stock, ", Discount: ", OLD.discount, ", Image: ", OLD.image
        );

        SET @new_values = CONCAT(
            "Name: ", NEW.product_name, ", Category: ", NEW.category, 
            ", Orientation: ", NEW.orientation, ", Description: ", NEW.description, 
            ", Buy Price: ", NEW.buy_price, ", Sell Price: ", NEW.sell_price, 
            ", Stock: ", NEW.stock, ", Discount: ", NEW.discount, ", Image: ", NEW.image
        );

        -- Cari nama admin dari updated_by
        SET @admin_name = (SELECT fullname 
                           FROM users 
                           WHERE id = NEW.updated_by AND role_id = 1
                           LIMIT 1);

        -- Insert ke tabel logs
        INSERT INTO product_logs (action, old_value, new_value, admin_name, date)
        VALUES (
            "UPDATE", 
            @old_values,
            @new_values,
            @admin_name,
            NOW()
        );
    END IF;
END;
        ');        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Hapus trigger jika ada
        DB::unprepared('DROP TRIGGER IF EXISTS after_update_product_trigger');
    }
};
