<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Intenta asociar reservas existentes a usuarios por email
        $users = DB::table('users')->pluck('id', 'email');

        DB::table('class_bookings')->select('id','email')->orderBy('id')->chunk(100, function ($rows) use ($users) {
            foreach ($rows as $r) {
                if ($r->email && isset($users[$r->email])) {
                    DB::table('class_bookings')->where('id', $r->id)->update(['user_id' => $users[$r->email]]);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No revert automático: dejamos user_id como venía
    }
};
