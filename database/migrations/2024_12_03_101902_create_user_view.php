<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $sql = "CREATE OR REPLACE VIEW user_view AS
                SELECT 
                    users.id,
                    users.fullname,
                    users.username,
                    users.email,
                    users.gender,
                    users.phone,
                    users.address,
                    users.created_at,
                    roles.role_name
                FROM users
                INNER JOIN roles ON users.role_id = roles.id";
        
        // Jalankan query untuk membuat view
        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        // Drop view jika migrasi dibatalkan
        DB::statement("DROP VIEW IF EXISTS user_view");
    }
};
