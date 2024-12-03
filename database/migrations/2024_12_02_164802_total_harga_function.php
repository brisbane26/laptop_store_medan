<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class TotalHargaFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
           CREATE FUNCTION Total_Harga(quantity INT, price DECIMAL(10,2))
            RETURNS DECIMAL(10,2)
            DETERMINISTIC
            BEGIN
    RETURN quantity * price;
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
        DB::unprepared('DROP FUNCTION IF EXISTS Total_Harga');
    }
}
