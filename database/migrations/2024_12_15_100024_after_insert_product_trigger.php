<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
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
        // Trigger untuk INSERT
        DB::unprepared('
        CREATE TRIGGER after_insert_product_trigger
        AFTER INSERT ON products
        FOR EACH ROW
        BEGIN
            -- Set nilai untuk new_values
            SET @new_values = CONCAT(
                "Name: ", NEW.product_name, ", Category: ", NEW.category, 
                ", Orientation: ", NEW.orientation, ", Description: ", NEW.description, 
                ", Buy Price: ", NEW.buy_price, ", Sell Price: ", NEW.sell_price, 
                ", Stock: ", NEW.stock, ", Discount: ", NEW.discount, ", Image: ", NEW.image
            );
        
            -- Cari nama admin dari created_by
            SET @admin_name = (SELECT fullname 
                               FROM users 
                               WHERE id = NEW.created_by AND role_id = 1
                               LIMIT 1);
        
            -- Insert ke tabel logs
            INSERT INTO product_logs (action, old_value, new_value, admin_name, date)
            VALUES (
                "INSERT", 
                NULL,
                @new_values,
                @admin_name,
                NOW()
            );
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
        DB::unprepared('DROP TRIGGER IF EXISTS after_insert_product_trigger');
    }
};
