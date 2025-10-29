<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            if (! Schema::hasColumn('contact_messages', 'gdpr_given')) {
                $table->boolean('gdpr_given')->default(false)->after('message');
            }
            if (! Schema::hasColumn('contact_messages', 'gdpr_at')) {
                $table->timestamp('gdpr_at')->nullable()->after('gdpr_given');
            }
        });
    }

    public function down(): void
    {
        Schema::table('contact_messages', function (Blueprint $table) {
            $table->dropColumn(['gdpr_given','gdpr_at']);
        });
    }
};
