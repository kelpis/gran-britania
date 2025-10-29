<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Copiar valores de meet_url a meeting_url cuando meeting_url es NULL
        DB::statement("UPDATE class_bookings SET meeting_url = meet_url WHERE meeting_url IS NULL AND meet_url IS NOT NULL");

        // Eliminar la columna legacy si existe
        Schema::table('class_bookings', function (Blueprint $table) {
            if (Schema::hasColumn('class_bookings', 'meet_url')) {
                $table->dropColumn('meet_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('class_bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('class_bookings', 'meet_url')) {
                $table->string('meet_url')->nullable()->after('meeting_url');
            }
        });

        // Restaurar valores desde meeting_url a meet_url
        DB::statement("UPDATE class_bookings SET meet_url = meeting_url WHERE meet_url IS NULL AND meeting_url IS NOT NULL");
    }
};
