<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('class_bookings', function (Blueprint $table) {
            if (! Schema::hasColumn('class_bookings', 'gdpr_given')) {
                $table->boolean('gdpr_given')->default(false)->after('notes');
            }
            if (! Schema::hasColumn('class_bookings', 'gdpr_at')) {
                $table->timestamp('gdpr_at')->nullable()->after('gdpr_given');
            }
        });
    }

    public function down(): void
    {
        Schema::table('class_bookings', function (Blueprint $table) {
            $table->dropColumn(['gdpr_given','gdpr_at']);
        });
    }
};
