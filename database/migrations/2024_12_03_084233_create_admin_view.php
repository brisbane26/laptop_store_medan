<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $sql = "
        CREATE OR REPLACE VIEW admin_view AS
        SELECT 
            id AS admin_id, -- Alias untuk kolom id
            fullname,
            username,
            email,
            phone,
            gender,
            address,
            created_at,
            updated_at
        FROM users
        WHERE role_id = 1; -- Hanya untuk admin
        ";

        DB::statement($sql);
    }

    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS admin_view");
    }
};

